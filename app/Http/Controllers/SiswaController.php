<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Support\Branding;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with('kelas')
            ->orderBy('nama')
            ->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.index', compact('siswa', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSiswa($request);

        Siswa::create($validated);

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load('kelas');

        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.edit', [
            'siswa' => $siswa,
            'kelas' => $kelas,
        ]);
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $this->validateSiswa($request, $siswa);

        $siswa->update($validated);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        $rows = collect($spreadsheet->getActiveSheet()->toArray(null, true, true, false));

        $headerIndex = $rows->search(function (array $row) {
            return Str::upper(trim((string) ($row[1] ?? ''))) === 'NIS'
                && Str::contains(Str::upper(trim((string) ($row[2] ?? ''))), 'NAMA SISWA');
        });

        if ($headerIndex === false) {
            return back()->withErrors(['file' => 'Format file tidak sesuai template data siswa.'])->withInput();
        }

        $dataRows = $rows->slice($headerIndex + 1)
            ->filter(fn (array $row) => filled($row[1] ?? null) && filled($row[2] ?? null));

        $stats = ['created' => 0, 'updated' => 0, 'skipped' => 0];

        foreach ($dataRows as $row) {
            $payload = $this->mapImportRow($row);

            if (! $payload) {
                $stats['skipped']++;
                continue;
            }

            $siswa = Siswa::where('nis', $payload['nis'])->first();

            if ($siswa) {
                $siswa->update($payload);
                $stats['updated']++;
                continue;
            }

            Siswa::create($payload);
            $stats['created']++;
        }

        return redirect()->route('siswa.index')->with(
            'success',
            "Impor selesai. {$stats['created']} siswa baru, {$stats['updated']} diperbarui, {$stats['skipped']} dilewati."
        );
    }

    public function cardPdf(Request $request, Siswa $siswa)
    {
        $siswa->load('kelas');
        $showAddress = $request->boolean('show_address', true);

        $pdf = Pdf::loadView('siswa.pdf-card', [
            'siswa' => $this->presentSiswaCard($siswa, $showAddress),
            'sekolah' => Branding::schoolName(),
            'tagline' => Branding::schoolTagline(),
            'logoPath' => Branding::logoPublicPath(),
            'brandInitials' => Branding::initials(),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('kartu-siswa-' . Str::slug($siswa->nama) . '.pdf');
    }

    public function massCardPdf(Request $request)
    {
        $request->validate([
            'kelas_id' => ['nullable', 'integer', 'exists:kelas,id'],
            'per_page' => ['nullable', 'integer'],
            'show_address' => ['nullable', 'boolean'],
        ]);

        $showAddress = $request->boolean('show_address', true);
        $kelasId = $request->integer('kelas_id');

        $query = Siswa::with('kelas')
            ->orderBy('kelas_id')
            ->orderBy('nama');

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $students = $query->get()
            ->map(fn (Siswa $siswa) => $this->presentSiswaCard($siswa, $showAddress));

        abort_if($students->isEmpty(), 404, 'Belum ada siswa untuk dicetak.');

        $perPage = (int) $request->integer('per_page', 8);
        if (! in_array($perPage, [1, 8], true)) {
            $perPage = 8;
        }

        $view = $perPage === 1 ? 'siswa.pdf-cards-single-per-page' : 'siswa.pdf-cards-mass';
        $paper = 'a4';
        $orientation = 'portrait';

        $pdf = Pdf::loadView($view, [
            'cards' => $students->chunk($perPage),
            'showAddress' => $showAddress,
            'sekolah' => Branding::schoolName(),
            'tagline' => Branding::schoolTagline(),
            'logoPath' => Branding::logoPublicPath(),
            'brandInitials' => Branding::initials(),
        ]);

        $pdf->setPaper($paper, $orientation);

        return $pdf->stream('kartu-siswa-' . $perPage . '-per-halaman.pdf');
    }

    protected function presentSiswaCard(Siswa $siswa, bool $showAddress = true): array
    {
        return [
            'id' => $siswa->id,
            'nis' => $siswa->nis,
            'nama' => $siswa->nama,
            'kelas' => $siswa->kelas->nama_kelas ?? 'Kelas',
            'tanggal_lahir' => $siswa->tanggal_lahir ? date('d/m/Y', strtotime($siswa->tanggal_lahir)) : '-',
            'jenis_kelamin' => $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            'alamat' => $showAddress ? ($siswa->alamat ?: '-') : null,
            'show_address' => $showAddress,
            'token_preview' => Str::upper(Str::limit($siswa->qr_token, 20, '...')),
            'qr_data_uri' => 'data:image/svg+xml;base64,' . base64_encode(QrCode::format('svg')->size(220)->margin(0)->generate($siswa->qr_token)),
        ];
    }

    protected function validateSiswa(Request $request, ?Siswa $siswa = null): array
    {
        return $request->validate([
            'nis' => [
                'required',
                'string',
                'max:50',
                Rule::unique('siswa', 'nis')->ignore($siswa?->id),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);
    }

    protected function mapImportRow(array $row): ?array
    {
        $nis = trim((string) ($row[1] ?? ''));
        $nama = trim((string) ($row[2] ?? ''));

        if ($nis === '' || $nama === '') {
            return null;
        }

        $jenisKelamin = Str::upper(trim((string) ($row[4] ?? '')));
        if (! in_array($jenisKelamin, ['L', 'P'], true)) {
            $jenisKelamin = 'L';
        }

        return [
            'nis' => $nis,
            'nama' => $nama,
            'tanggal_lahir' => $this->parseTanggalLahir($row[3] ?? null),
            'jenis_kelamin' => $jenisKelamin,
            'kelas_id' => $this->resolveKelasId((string) ($row[5] ?? '')),
            'keterangan' => $this->cleanNullableString($row[6] ?? null),
            'alamat' => $this->cleanNullableString($row[7] ?? null),
        ];
    }

    protected function parseTanggalLahir(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value))->toDateString();
            }

            if ($value instanceof \DateTimeInterface) {
                return Carbon::instance($value)->toDateString();
            }

            $text = trim((string) $value);
            foreach (['d/m/Y', 'd-m-Y', 'Y-m-d'] as $format) {
                try {
                    return Carbon::createFromFormat($format, $text)->toDateString();
                } catch (\Throwable) {
                }
            }

            return Carbon::parse($text)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    protected function resolveKelasId(string $kelasLabel): int
    {
        $kelasLabel = trim($kelasLabel);
        $normalized = Str::upper($kelasLabel);

        $candidates = Collection::make([
            $kelasLabel,
            'TK ' . $normalized,
            'Kelompok ' . $normalized,
        ])->filter()->unique();

        $kelas = Kelas::query()
            ->where(function ($query) use ($candidates) {
                foreach ($candidates as $candidate) {
                    $query->orWhereRaw('UPPER(nama_kelas) = ?', [Str::upper($candidate)]);
                }
            })
            ->first();

        if (! $kelas) {
            $kelas = Kelas::create([
                'nama_kelas' => Str::startsWith($normalized, 'TK ') ? $normalized : 'TK ' . $normalized,
                'tahun_ajaran' => $this->defaultTahunAjaran(),
            ]);
        }

        return $kelas->id;
    }

    protected function defaultTahunAjaran(): string
    {
        $existing = Kelas::query()->whereNotNull('tahun_ajaran')->value('tahun_ajaran');

        if ($existing) {
            return $existing;
        }

        $startYear = now()->month >= 7 ? now()->year : now()->year - 1;

        return $startYear . '/' . ($startYear + 1);
    }

    protected function cleanNullableString(mixed $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : Str::limit($text, 255, '');
    }
}
