@if(!empty($q->key) && $q->key =='create')
<section class="alert alert-info" role="alert">
	<div class="card card-box">
		<div class="card-body">
			<div class="card">
				<div class="card-body">
					<div class="message ml-3">
						<div class="row">
							<div class="col-md">
								<img src="{{ asset('man.png') }}" class="float-left rounded-circle" width="50%">
							</div>
							<div class="col-md">
								<h5 class="card-title">{{ ucfirst(strtolower($q->detail->nama_nasabah)) }}</h5>
								<h6 class="card-subtitle mb-2 text-muted">{{ $q->detail->no_rekening }}</h6>
							</div>
							<div class="col-md">
								<strong>OS Pokok</strong>
								<h5>Rp. {{ number_format($q->detail->baki_debet) }}</h5>
								<strong>Angsuran Tertunggak</strong>
								<h5>Rp. {{ number_format($q->detail->total_tunggakan) }}</h5>
							</div>
							<div class="col-md">
								<strong>Angsuran</strong>
								<h5>Rp. {{ number_format($q->detail->angsuran) }}</h5>
								<strong>Denda Angsuran</strong>
								<h5>Rp. {{ number_format($q->detail->total_tagihan) }}</h5>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<form enctype="multipart/form-data" class="tab-wizard wizard-circle wizard" id="wizard-payment" url="{{ url('assigments-payment-store') }}">
@endif
<div class="row">
	<div class="col-md">
		<div class="form-group">
			<label >Task Code : </label>
			<input id="photo" type="hidden" name="take_foto" value=""/>
			<input type="text" class="form-control" readonly="" name="task_code" value="{!! !empty($q) ? $q->task_code : '' !!}">
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="task_code"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Angsuran : </label>
			<input type="hidden" id="total_tunggakan" value="{!! ($q->key=='edit') ? $q->total_tunggakan : $q->detail->total_tunggakan !!}">
			<input type="text" class="form-control angsuran angsuran-values" name="angsuran" value="{!! ($q->key=='edit') ? $q->angsuran : '' !!}" onkeyup="hitungSisaAngsuran();">
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="angsuran"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Denda : </label>
			<input type="hidden" class="denda-angsuran" value="{!! ($q->key=='edit') ? $q->denda : $q->detail->total_tagihan !!}">
			<input type="text" class="form-control" name="bayar_denda" value="{!! ($q->key=='edit') ? $q->denda : '' !!}" onkeyup="hitungTotalBayar();">
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="bayar_denda"></p></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md">
		<div class="form-group">
			<label >Collect Fee : </label>
			<input type="text" class="form-control" name="collect_fee" value="{!! ($q->key=='edit') ? $q->collect_fee : round($q->detail->collect_fee) !!}" onkeyup="hitungTotalBayar();">
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="collect_fee"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Titipan : </label>
			<input type="text" class="form-control" name="titipan" value="{!! ($q->key=='edit') ? $q->titipan : '' !!}" onkeyup="hitungTotalBayar();">
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="titipan"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Angsuran Ke : </label>
			<select class="form-control" name="ang_ke">
				@foreach ($q->angs as $key => $value)
				<option value="{{$value->ang_ke}}" {{( $q->ang_ke == $value->ang_ke)?'selected':'' }} >{{$value->ang_ke}}</option>
				@endforeach
			</select>
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="ang_ke"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Tenor : </label>
			<input type="text" class="form-control" name="tenor" value="{!! ($q->key=='edit') ? $q->tenor : $q->detail->tenor !!}">
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="tenor"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >No. BSS : </label>
			<input type="text" class="form-control" name="no_bss" value="{{ $q->no_bss }}">
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="no_bss"></p></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md">
		<div class="form-group">
			<label >Total Bayar Angsuran : </label>
			<input type="text" class="form-control" name="total_bayar_angsuran" value="{!! ($q->key=='edit') ? $q->total_bayar : '' !!}">
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="total_bayar_angsuran"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Sisa Angsuran : </label>
			<input type="text" class="form-control sisa_angsuran" name="sisa_angsuran" value="{!! ($q->key=='edit') ? $q->sisa_angsuran : '' !!}" >
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="sisa_angsuran"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Sisa Denda : </label>
			<input type="text" class="form-control" name="sisa_denda" value="{!! ($q->key=='edit') ? $q->sisa_denda : '' !!}">
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="sisa_denda"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label>Tanggal Jatuh Tempo</label>
			<input class="form-control date-picker" placeholder="Select Date" type="date" value="{!! ($q->key=='edit') ? $q->tgl_jt_tempo : $q->detail->tgl_jt_tempo !!}" name="tgl_jt">
		</div>
	</div>
</div>
@if(!empty($q->key) && $q->key =='create')
<div class="row" id="canvas-signature" style="display:block">
	<div class="col-md">
		<div class="card card-box text-center">
			<div class="card-body">
				<h5 class="card-title" id="text-signature">Nasabah</h5>
				<canvas id="signature-pad" class="signature-pad"></canvas>
				<input id="signature64Nasabah" name="signature64Nasabah" type="hidden" value="" />
				<input id="signature64Collection" name="signature64Collection" type="hidden" value="" />
			</div>
			<div class="card-footer justify-content-center">
				<button id="clear" class="btn btn-danger" type="button">Clear</button>
				<button id="saveNasabah" class="btn btn-primary" type="button">Save Signature Nasabah</button>
				<button id="saveCollection" class="btn btn-primary" type="button">Save Signature Collection</button>
			</div>
		</div>
	</div>
</div>
</form>
<div class="modal-footer">
	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	<button type="button" class="btn btn-primary submit-now">Save changes</button>
</div>
@endif
