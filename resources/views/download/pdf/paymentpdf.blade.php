
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Example 1</title>
    <link rel="stylesheet" href="{{ public_path('stylepdf.css')  }}" media="all" />
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="{{public_path('headOL.png')}}" width="100%">
      </div>
      <h1>BUKTI SETORAN SEMENTARA</h1>
      <table>
          <tr>
              <th>
                  <table style="text-align: left;">
                      <tr>
                          <th style="text-align: left;">Sudah Diterima Dari</th>
                          <td>  : {{ ucfirst(strtolower(Auth::user()->name)) }}</td>
                      </tr>
                      <tr>
                          <th style="text-align: left;">No. Kontrak</th>
                          <td>  : {{ $x->no_rekening }}</td>
                      </tr>
                      <tr>
                          <th style="text-align: left;">No. Rekening</th>
                          <td>  : {{ $x->no_rekening }}</td>
                      </tr>
                      <tr>
                          <th style="text-align: left;">Atas Nama</th>
                          <td>  : {{ ucfirst(strtolower($x->nama_nasabah)) }}</td>
                      </tr>
                  </table>
              </th>
              <th width="30%"></th>
              <th>
                  <table style="text-align: left;">
                      <tr>
                          <th style="text-align: left;">No</th>
                          <td>  : KMI <u>{{ $q->no_bss }}</u></td>
                      </tr>
                      <tr>
                          <th style="text-align: left;">Tgl. JT</th>
                          <td>  : {{ date('d F Y', strtotime($q->tgl_jt_tempo)) }}</td>
                      </tr>
                      <tr>
                          <th style="text-align: left;"></th>
                          <td></td>
                      </tr>
                      <tr>
                          <th style="text-align: left;"></th>
                          <td></td>
                      </tr>
                  </table>
              </th>
          </tr>
      </table>
    </header>
    <main>
        <div id="notices" style="margin-bottom: 10;">
            <div style="font-size: 1.2em;">Untuk pembayaran dengan rincian sebagai berikut.</div>
        </div>
        <table style="text-align: left;">
          <tr>
              <th style="text-align: left;">Angsuran Ke</th>
              <td> <u>{{ $q->ang_ke }}</u> <strong>dari</strong> <u>{{ $q->tenor }}</u></td>
              <th style="text-align: left;">: Rp. </th>
              <td><u>{{ number_format($q->angsuran,0,',','.') }}</u></td>
          </tr>
          <tr>
              <th style="text-align: left;">Denda Keterlambatan</th>
              <td></td>
              <th style="text-align: left;">: Rp. </th>
              <td><u>{{ number_format($q->denda,0,',','.') }}</u></td>
          </tr>
          <tr>
              <th style="text-align: left;">Titipan</th>
              <td></td>
              <th style="text-align: left;">: Rp. </th>
              <td><u>{{ number_format($q->titipan,0,',','.') }}</u></td>
          </tr>
          <tr>
              <th style="text-align: left;">Biaya Tagih</th>
              <td></td>
              <th style="text-align: left;">: Rp. </th>
              <td><u>{{ number_format($q->collect_fee,0,',','.') }}</u></td>
          </tr>
          <tr>
              <th style="text-align: left;">Total</th>
              <td></td>
              <th style="text-align: left;">: Rp. </th>
              <td><u>{{ number_format($q->total_bayar,0,',','.') }}</u></td>
          </tr>
      </table>
      <div id="notices" style="margin-bottom: 10;">
        <div style="font-size: 1.2em;">Cara Pembayaran.</div>
      </div>
      <table style="text-align: left;">
          <tr>
              <th style="text-align: left;">Tunai</th>
              <td>  : Rp. <u>{{ number_format($q->total_bayar,0,',','.') }}</u></td>
          </tr>
          <tr>
              <th style="text-align: left;">GIRO</th>
              <td>  : <strong>Bank</strong> <u>-</u> <strong>Tgl. JT</strong> <u>{{ date('d F Y', strtotime($q->tgl_jt_tempo)) }}</u></td>
          </tr>
      </table>
      <div id="notices" style="margin-top: 10;">
        <div>Syarat Dan Ketentuan:</div>
        <div class="notice">
            <table>
                <tr>
                    <th>1</th>
                    <td>. Pembayaran tunai dianggap sah setelah disetorkan oleh petugas lapangan ke bagian Teller PT. BPR Kredit Mandiri Indonesia</td>
                </tr>
                <tr>
                    <th>2</th>
                    <td>. Pembayaran dengan Cek/Giro dianggap sah setelah masuk ke rekening PT. BPR Kredit Mandiri Indonesia</td>
                </tr>
                <tr>
                    <th>3</th>
                    <td>. Pembayaran dengan Cek/Giro harus atas nama PT. BPR Kredit Mandiri Indonesia</td>
                </tr>
                <tr>
                    <th>4</th>
                    <td>. Nasabah diharapkan untuk melakukan konfirmasi ke bagian <i>Collection</i> PT. BPR Kredit Mandiri Indonesia setelah melakukan pembayaran melalui petugas lapangan.</td>
                </tr>
                <tr>
                    <th>5</th>
                    <td>. Bukti setoran sementara ini harus disimpan oleh nasabah dan dibawa pada saat pengambilan jaminan.</td>
                </tr>
            </table>
        </div>
      </div>
    </main>
    <table width="100%">
          <tr>
              <th>
                  <table style="text-align: left;" width="100%">
                      <tr>
                          <th style="text-align: center;">Diberikan Oleh</th>
                      </tr>
                      <tr>
                          <th style="text-align: center;"><img src="{{public_path($q->file_ttd_collection)}}" width="20%" style="text-align: center;">
                          </th>
                      </tr>
                      <tr>
                          <th style="text-align: center;">{{ ucfirst(strtolower(Auth::user()->name)) }}</th>
                      </tr>
                      <tr>
                          <th style="text-align: center;"><strong>Tanggal :</strong> {{ date('d F Y', strtotime(substr($q->created_at,0,10))) }}</th>
                      </tr>
                  </table>
              </th>
              <th width="10%"></th>
              <th>
                  <table style="text-align: left;" width="100%">
                      <tr>
                          <th style="text-align: center;">Diterima Oleh</th>
                      </tr>
                      <tr>
                          <th style="text-align: center;"><img src="{{public_path($q->file_ttd_nasabah)}}" width="20%" style="text-align: center;">
                          </th>
                      </tr>
                      <tr>
                          <th style="text-align: center;">{{ ucfirst(strtolower($x->nama_nasabah)) }}</th>
                          <td>: </td>
                      </tr>
                      <tr>
                          <th style="text-align: center;">Tanggal : {{ date('d F Y', strtotime(substr($q->created_at,0,10))) }}</th>
                      </tr>
                  </table>
              </th>
          </tr>
      </table>
    <footer>
      Lembaran bukti pembayaran ini untuk nasabah.
    </footer>
  </body>
</html>