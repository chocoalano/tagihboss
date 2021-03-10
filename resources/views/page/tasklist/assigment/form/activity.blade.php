@if(!empty($q->key))
<form enctype="multipart/form-data" class="tab-wizard wizard-circle wizard" id="wizard-activity" url="{{ url('assigments-activity-store') }}">
@endif
<div class="row">
	<div class="col-md">
		<div class="form-group">
			<label >Task Code : </label>
			<input id="photo" type="hidden" name="take_foto" value=""/>
			<input type="text" class="form-control" readonly="" name="task_code" value="{{ $q->task_code }}">
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="task_code"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Kunjungan Ke : </label>
			<input type="number" class="form-control" name="kunjungan_ke" value="{{ $q->kunjungan_ke }}">
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="task_code"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Bertemu Dengan : </label>
			<select class="custom-select form-control" name="bertemu">
				<option value="">Selected</option>
				<option value="debitur/pasangan" {{ ($q->bertemu=='debitur/pasangan')?'selected':'' }} >Debitur/pasangan</option>
				<option value="keluarga" {{ ($q->bertemu=='keluarga')?'selected':'' }} >Keluarga</option>
				<option value="tetangga" {{ ($q->bertemu=='tetangga')?'selected':'' }} >Tetangga</option>
				<option value="orang lain" {{ ($q->bertemu=='orang lain')?'selected':'' }} >Orang lain</option>
			</select>
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="bertemu"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Karakter : </label>
			<select class="custom-select form-control" name="karakter_debitur">
				<option value="">Selected</option>
				<option value="noncorporative" {{ ($q->karakter_debitur=='noncorporative')?'selected':'' }} >Tidak Kooperatif</option>
				<option value="corporative" {{ ($q->karakter_debitur=='corporative')?'selected':'' }} >Kooperatif</option>
				<option value="lainnya" {{ ($q->karakter_debitur=='lainnya')?'selected':'' }} >Lainya</option>
			</select>
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="karakter_debitur"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Total Penghasilan : </label>
			<input type="text" class="form-control" name="total_penghasilan" value="{{ $q->total_penghasilan }}" onkeyup="this.value = formatRupiah(this.value, 'Rp. ');">
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="total_penghasilan"></p></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md">
		<div class="form-group">
			<label >Kondisi Pekerjaan : </label>
			<select class="custom-select form-control" name="kondisi_pekerjaan">
				<option value="">Selected</option>
				@foreach ($q->kondpekerjaan as $key => $value)
				<option value="{{$value->options}}" {{ ($q->kondisi_pekerjaan==$value->options)?'selected':'' }} >{{$value->options}}</option>
				@endforeach
			</select>
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="kondisi_pekerjaan"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Asset Debitur : </label>
			<select class="custom-select form-control" name="asset_debt">
				<option value="">Selected</option>
				@foreach ($q->asset as $key => $value)
					<option value="{{$value->options}}" {{ ($q->asset_debt==$value->options)?'selected':'' }} >{{$value->options}}</option>
				@endforeach
			</select>
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="asset_debt"></p></div>
		</div>
	</div>
	<div class="col-md date-picker-janji-bayar" style="display: none;">
		<div class="form-group">
			<label>Tanggal Janji Bayar</label>
			<input class="form-control date-picker" placeholder="Select Date" type="date" value="{{ $q->tgl_janji_byr }}" name="tgl_janji_byr">
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Janji Bayar Debitur : </label>
			<select class="custom-select form-control" name="janji_byr">
				<option value="">Selected</option>
				<option value="Y" {{ ($q->janji_byr=='Y')?'selected':'' }} >Yes</option>
				<option value="N" {{ ($q->janji_byr=='N')?'selected':'' }} >No</option>
			</select>
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="janji_byr"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Next Actions : </label>
			<select class="custom-select form-control" name="next_action">
				<option value="">Selected</option>
				@foreach ($q->nextact as $key => $value)
					<option value="{{$value->options}}" {{ ($q->next_action==$value->options)?'selected':'' }} >{{$value->options}}</option>
				@endforeach
			</select>
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="next_action"></p></div>
		</div>
	</div>
	<div class="col-md">
		<div class="form-group">
			<label >Case Category : </label>
			<select class="custom-select form-control" name="case_category">
				<option value="">Selected</option>
				@foreach ($q->casecategory as $key => $value)
					<option value="{{$value->options}}" {{ ($q->case_category==$value->options)?'selected':'' }} >{{$value->options}}</option>
				@endforeach
			</select>
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="case_category"></p></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md">
		<div class="form-group">
			<label >Keterangan : </label>
			<textarea class="form-control" name="keterangan">{{ $q->keterangan }}</textarea>
			<div class="form-control-feedback" style="display:none"><p class="text-danger" id="keterangan"></p></div>
		</div>
	</div>
</div>
@if(!empty($q->key))
</form>
<div class="modal-footer">
	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	<button type="button" class="btn btn-primary submit-now">Save changes</button>
</div>
@endif
