<form id="form-edit" url="{{ $q->url }}">
	<div class="modal-header">
		<h4 class="modal-title" id="myLargeModalLabel">Edit & Updated</h4>
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	</div>
	<div class="modal-body">
		@if($key=="tt" || $key=="jm")
		<input type="hidden" name="param" value="{{$q->param}}">
		<input type="hidden" name="code" value="{{$q->task_code}}">
		<div class="row">
			<div class="col-md">
				<div class="form-group">
					<label>Kondisi {{ ($q->param == "tt")?"Tempat Tinggal":"Jaminan" }} :</label>
					<select class="custom-select form-control" name="kondisi">
						@if($key=="tt")
						<option value="">Selected</option>
						<option value="layak-huni" {{ ($q->kondisi_tempat=='layak-huni')?'selected':'' }} >Layak Huni</option>
						<option value="akses-mudah" {{ ($q->kondisi_tempat=='akses-mudah')?'selected':'' }} >Akses Mudah</option>
						<option value="tidak-dekat-jalan-raya" {{ ($q->kondisi_tempat=='tidak-dekat-jalan-raya')?'selected':'' }} >Tidak dekat jalan raya</option>
						@else
						<option value="layak-huni" {{ ($q->kondisi_tempat=='layak-huni')?'selected':'' }} >Layak Huni</option>
						<option value="mudah-diakses" {{ ($q->kondisi_tempat=='mudah-akses')?'selected':'' }} >Mudah Diakses</option>
						<option value="langsung-berhubungan-dengan-jalan-raya" {{ ($q->kondisi_tempat=='langsung-berhubungan-dengan-jalan-raya')?'selected':'' }} >Langsung berhubungan dengan jalan raya</option>
						@endif
					</select>
					<div class="form-control-feedback" style="display:none"><p class="text-danger" id="kondisi"></p></div>
				</div>
			</div>
			<div class="col-md">
				<div class="form-group">
					<label >Latitude : </label> <a href="#" onclick="refreshcordinate()"> Refresh Cordinates</a>
					<input type="text" class="form-control" readonly="" name="latitude" value="{{ $q->latitude }}">
					<div class="form-control-feedback" style="display:none"><p class="text-danger" id="latitude"></p></div>
				</div>
			</div>
			<div class="col-md">
				<div class="form-group">
					<label>Longitude : </label> <a href="#" onclick="refreshcordinate()"> Refresh Cordinates</a>
					<input type="email" class="form-control" readonly="" name="longitude" value="{{ $q->longitude }}">
					<div class="form-control-feedback" style="display:none"><p class="text-danger" id="longitude"></p></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md">
				<div class="form-group">
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="image1" name="image1" accept="image/*">
						<label class="custom-file-label">Take Photo</label>
					</div>
					<div class="form-control-feedback" style="display:none"><p class="text-danger" id="image1"></p></div>
				</div>
			</div>
			<div class="col-md">
				<div class="form-group">
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="image2" name="image2" accept="image/*">
						<label class="custom-file-label">Take Photo</label>
					</div>
					<div class="form-control-feedback" style="display:none"><p class="text-danger" id="image2"></p></div>
				</div>
			</div>
			<div class="col-md">
				<div class="form-group">
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="image3" name="image3" accept="image/*">
						<label class="custom-file-label">Take Photo</label>
					</div>
					<div class="form-control-feedback" style="display:none"><p class="text-danger" id="image3"></p></div>
				</div>
			</div>
		</div>
		@elseif($key=='activity')
			@include('page.tasklist.assigment.form.activity')
		@elseif($key=='payment')
			@include('page.tasklist.assigment.form.payment')
		@endif
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>