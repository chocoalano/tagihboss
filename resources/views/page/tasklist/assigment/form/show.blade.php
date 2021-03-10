    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Task List</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        @if($key=="tt" || $key=="jm")
        <div class="timeline mb-30">
        	<ul>
        		<li>
        			<div class="timeline-date">
        				Detail
        			</div>
        			<div class="timeline-desc card-box">
        				<h5 class="card-title weight-500">{{ ($key=="tt")?'Data Tempat Tinggal':'Data Jaminan' }}</h5>
        				<div class="pd-20">
        					<table class="table-sm">
        						<tr>
        							<th>Kode</th>
        							<td>: {{ $q->task_code }}</td>
        						</tr>
        						<tr>
        							<th>No. Pinjaman</th>
        							<td>: {{ $q->detail->no_rekening }}</td>
        						</tr>
        						<tr>
        							<th>Angsuran Ke</th>
        							<td>: {{ $q->detail->Detail->ang_ke }}</td>
        						</tr>
        						<tr>
        							<th>OS Pokok</th>
        							<td>: {{ 'Rp. '.number_format($q->detail->os_pokok) }}</td>
        						</tr>
        						<tr>
        							<th>Collect Fee</th>
        							<td>: {{ 'Rp. '.number_format($q->detail->collect_fee) }}</td>
        						</tr>
        						<tr>
        							<th>Total Tagihan</th>
        							<td>: {{ 'Rp. '.number_format($q->detail->total_tagihan) }}</td>
        						</tr>
        						<tr>
        							<th>Kordinat Garis Lintang Lokasi</th>
        							<td>: {{ $q->latitude }}</td>
        						</tr>
        						<tr>
        							<th>Kordinat Garis Bujur Lokasi</th>
        							<td>: {{ $q->longitude }}</td>
        						</tr>
        					</table>
        				</div>
        			</div>
        		</li>
        		<li>
        			<div class="timeline-date">
        				Photo
        			</div>
        			<div class="timeline-desc card-box">
        				<div class="card card-box">
        					<div class="card-body">
        						<h5 class="card-title weight-500">Foto Lokasi Visit {{ ($key=="tt")?'Data Tempat Tinggal':'Data Jaminan' }}</h5>
        						<div class="row">
        							<div class="col-md">
        								<img class="card-img-top" src="{{ asset($q->file1) }}" alt="Card image cap">
        							</div>
        							<div class="col-md">
        								<img class="card-img-top" src="{{ asset($q->file2) }}" alt="Card image cap">
        							</div>
        							<div class="col-md">
        								<img class="card-img-top" src="{{ asset($q->file3) }}" alt="Card image cap">
        							</div>
        						</div>
        					</div>
        				</div>
        			</div>
        		</li>
        	</ul>
        </div>
        @elseif($key=='masters')
        <div class="pd-20">
        	<div class="row">
        		<div class="col-md">
        			<table class="table-sm">
        				<tr>
        					<th>Kode</th>
        					<td>: {{ $q->task_code }}</td>
        				</tr>
        				<tr>
        					<th>No. Pinjaman</th>
        					<td>: {{ $q->no_rekening }}</td>
        				</tr>
        				<tr>
        					<th>Angsuran Ke</th>
        					<td>: {{ $q->Detail->ang_ke }}</td>
        				</tr>
        				<tr>
        					<th>OS Pokok</th>
        					<td>: {{ 'Rp. '.number_format($q->os_pokok) }}</td>
        				</tr>
        				<tr>
        					<th>Collect Fee</th>
        					<td>: {{ 'Rp. '.number_format($q->collect_fee) }}</td>
        				</tr>
        				<tr>
        					<th>Total Tagihan</th>
        					<td>: {{ 'Rp. '.number_format($q->total_tagihan) }}</td>
        				</tr>
        			</table>
        		</div>
        		<div class="col-md">
        			<table class="table-sm">
        				<tr>
        					<th>FT. Angsuran</th>
        					<td>: {{ $q->Master->ft_angsuran }}</td>
        				</tr>
        				<tr>
        					<th>Tanggal Jatuh Tempo</th>
        					<td>: {{ $q->Detail->tgl_jt_tempo }}</td>
        				</tr>
        				<tr>
        					<th>FT. Hari</th>
        					<td>: {{ $q->Detail->ft_hari }}</td>
        				</tr>
        				<tr>
        					<th>Angsuran</th>
        					<td>: {{ 'Rp. '.number_format($q->Detail->angsuran) }}</td>
        				</tr>
        				<tr>
        					<th>Denda</th>
        					<td>: {{ 'Rp. '.number_format($q->Detail->denda) }}</td>
        				</tr>
        			</table>
        		</div>
        	</div>
        </div>
        @elseif($key=='activity')
        <div class="pd-20">
            <div class="row">
                <div class="col-md">
                    <table class="table-sm">
                        <tr>
                            <th>Kode</th>
                            <td>: {{ $q->task_code }}</td>
                        </tr>
                        <tr>
                            <th>No. Pinjaman</th>
                            <td>: {{ $q->Task->no_rekening }}</td>
                        </tr>
                        <tr>
                            <th>OS Pokok</th>
                            <td>: {{ 'Rp. '.number_format($q->Task->os_pokok) }}</td>
                        </tr>
                        <tr>
                            <th>Collect Fee</th>
                            <td>: {{ 'Rp. '.number_format($q->Task->collect_fee) }}</td>
                        </tr>
                        <tr>
                            <th>Total Tagihan</th>
                            <td>: {{ 'Rp. '.number_format($q->Task->total_tagihan) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md">
                    <table class="table-sm">
                        <tr>
                            <th>Bertemu Dengan</th>
                            <td>: {{ $q->bertemu }}</td>
                        </tr>
                        <tr>
                            <th>Karakter Debitur</th>
                            <td>: {{ $q->karakter_debitur }}</td>
                        </tr>
                        <tr>
                            <th>Asset Debitur</th>
                            <td>: {{ $q->asset_debt }}</td>
                        </tr>
                        <tr>
                            <th>Total Penghasilan</th>
                            <td>: {{ 'Rp. '.number_format($q->total_penghasilan) }}</td>
                        </tr>
                        <tr>
                            <th>Denda</th>
                            <td>: {{ 'Rp. '.number_format($q->denda) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md">
                    <table class="table-sm">
                        <tr>
                            <th>JB</th>
                            <td>: {{ ($q->janji_byr=='Y')? 'Debitur Berjanji':'Debitur Tidak Berjanji' }}</td>
                        </tr>
                        @if($q->janji_byr=='Y')
                        <tr>
                            <th>TJB</th>
                            <td>: {{ $q->tgl_janji_byr }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Case</th>
                            <td>: {{ $q->case_category }}</td>
                        </tr>
                        <tr>
                            <th>Pekerjaan</th>
                            <td>: {{ $q->kondisi_pekerjaan }}</td>
                        </tr>
                        <tr>
                            <th>Action</th>
                            <td>: {{ $q->next_action }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>: {{ $q->keterangan }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="justify-content-center">
                <div class="card card-box">
                    <img class="card-img" src="{{ asset($q->file) }}" alt="Card image Activity">
                </div>
            </div>
        </div>
        @elseif($key=='payment')
        <div class="pd-20">
            <div class="row">
                <div class="col-md">
                    <table class="table-sm">
                        <tr>
                            <th>Kode</th>
                            <td>: {{ $q->task_code }}</td>
                        </tr>
                        <tr>
                            <th>No. Pinjaman</th>
                            <td>: {{ $q->Task->no_rekening }}</td>
                        </tr>
                        <tr>
                            <th>OS Pokok</th>
                            <td>: {{ 'Rp. '.number_format($q->Task->os_pokok) }}</td>
                        </tr>
                        <tr>
                            <th>Collect Fee</th>
                            <td>: {{ 'Rp. '.number_format($q->Task->collect_fee) }}</td>
                        </tr>
                        <tr>
                            <th>Total Tagihan</th>
                            <td>: {{ 'Rp. '.number_format($q->Task->total_tagihan) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md">
                    <table class="table-sm">
                        <tr>
                            <th>Angsuran Ke</th>
                            <td>: {{ $q->ang_ke }}</td>
                        </tr>
                        <tr>
                            <th>Tenor</th>
                            <td>: {{ $q->tenor }}</td>
                        </tr> 
                        <tr>
                            <th>Bayar Angsuran</th>
                            <td>: {{ 'Rp. '.number_format($q->angsuran,2) }}</td>
                        </tr>
                        <tr>
                            <th>Denda</th>
                            <td>: {{ 'Rp. '.number_format($q->denda,2) }}</td>
                        </tr>
                        <tr>
                            <th>Collect Fee</th>
                            <td>: {{ 'Rp. '.number_format($q->collect_fee,2) }}</td>
                        </tr>
                        <tr>
                            <th>Titipan</th>
                            <td>: {{ 'Rp. '.number_format($q->titipan,2) }}</td>
                        </tr>
                        <tr>
                            <th>Total Bayar</th>
                            <td>: {{ 'Rp. '.number_format($q->total_bayar,2) }}</td>
                        </tr>
                        <tr>
                            <th>Sisa Angsuran</th>
                            <td>: {{ 'Rp. '.number_format($q->sisa_angsuran,2) }}</td>
                        </tr>
                        <tr>
                            <th>Sisa Denda</th>
                            <td>: {{ 'Rp. '.number_format($q->sisa_denda,2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="justify-content-center">
                <div class="card card-box">
                    <img class="card-img" src="{{ asset($q->file) }}" alt="Card image Activity">
                </div>
            </div>
        </div>
         <!-- {{ dd($q) }} -->
        @endif
    </div>