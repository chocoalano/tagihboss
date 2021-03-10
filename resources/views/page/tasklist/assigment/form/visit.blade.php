<div class="row">
	<div class="alert alert-info" role="alert">
		Jika <a href="#" class="alert-link">tidak diperlukan</a>, anda dapat mengosongkan form "Tempat tinggal" untuk melanjutkan ke form "Jaminan". <code><button type="button" link="{{url('check-location')}}" class="link check-location-map btn btn-info">Check location is here</button></code>
	</div>	
	<div class="col-md">
		<label class="weight-600">Tempat Tinggal</label>
		<div class="custom-control custom-radio mb-5">
			<input type="radio" id="customRadioVisit1" name="customRadioVisit" class="custom-control-input" value="tt">
			<label class="custom-control-label" for="customRadioVisit1">Tempat Tinggal</label>
		</div>
	</div>
	<div class="col-md">
		<label class="weight-600">Jaminan</label>
		<div class="custom-control custom-radio mb-5">
			<input type="radio" id="customRadioVisit2" name="customRadioVisit" class="custom-control-input" value="jm">
			<label class="custom-control-label" for="customRadioVisit2">Jaminan</label>
		</div>
	</div>
	<div class="col-md">
		<label class="weight-600">Tempat Tinggal & Jaminan</label>
		<div class="custom-control custom-radio mb-5">
			<input type="radio" id="customRadioVisit3" name="customRadioVisit" class="custom-control-input" value="tt&jm">
			<label class="custom-control-label" for="customRadioVisit3">Tempat Tinggal & Jaminan</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md" id="form-tt">
		<form id="form-tempat-tinggal-visit" enctype="multipart/form-data" url="{{ url('assigments-visit-store-tempat-tinggal') }}">
			<input type="hidden" name="task_code" value="{{$q->task_code}}">
			<div class="card card-box text-center">
				<div class="card-body">
					<section class="mb-3">	
						<div class="row">
							<div class="col-md">
								<div class="form-group">
									<label>Kondisi Tempat Tinggal :</label>
									<select class="custom-select form-control" name="kondisi_tempat_tinggal">
										<option value="">Selected</option>
										<option value="layak-huni">Layak Huni</option>
										<option value="akses-mudah">Akses Mudah</option>
										<option value="tidak-dekat-jalan-raya">Tidak dekat jalan raya</option>
									</select>
									<div class="form-control-feedback" style="display:none"><p class="text-danger" id="kondisi_tempat_tinggal"></p></div>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label >Latitude : </label> <a href="#" onclick="refreshcordinate()"> Refresh Cordinates</a>
									<input type="text" class="form-control" readonly="" name="latitude_tempat_tinggal">
									<div class="form-control-feedback" style="display:none"><p class="text-danger" id="latitude_tempat_tinggal"></p></div>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label>Longitude : </label> <a href="#" onclick="refreshcordinate()"> Refresh Cordinates</a>
									<input type="email" class="form-control" readonly="" name="longitude_tempat_tinggal">
									<div class="form-control-feedback" style="display:none"><p class="text-danger" id="longitude_tempat_tinggal"></p></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md">
								<div class="form-group">
									<input type="hidden" name="imageTempatTinggal1">
									<button class="btn btn-info" type="button" id="foto-tempat-tinggal-1">Foto 1 <i class="icon-copy dw dw-photo-camera1"></i></button>
									<div class="form-control-feedback" style="display:none"><p class="text-danger" id="imageTempatTinggal1"></p></div>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<input type="hidden" name="imageTempatTinggal2">
									<button class="btn btn-info" type="button" id="foto-tempat-tinggal-2">Foto 2 <i class="icon-copy dw dw-photo-camera1"></i></button>
									<div class="form-control-feedback" style="display:none"><p class="text-danger" id="imageTempatTinggal2"></p></div>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<input type="hidden" name="imageTempatTinggal3">
									<button class="btn btn-info" type="button" id="foto-tempat-tinggal-3">Foto 3 <i class="icon-copy dw dw-photo-camera1"></i></button>
									<div class="form-control-feedback" style="display:none"><p class="text-danger" id="imageTempatTinggal3"></p></div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="card-footer" id="button-form-tt">
					<button class="btn btn-primary" type="submit">Save Tempat Tinggal</button>
					<button type="button" class="btn btn-danger canceled-now-wizard" data-dismiss="modal">Closed Modal</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md" id="form-jm">
		<form id="form-jaminan-visit" enctype="multipart/form-data" url="{{ url('assigments-visit-store-jaminan') }}">
			<input type="hidden" name="task_code" value="{{$q->task_code}}">
			<div class="card card-box text-center">
				<div class="card-body">
					<section class="mb-3">
						<div class="row">
							<div class="col-md">
								<div class="form-group">
									<label>Kondisi Jaminan Sekarang:</label>
									<select class="custom-select form-control" name="kondisi_jaminan">
										<option value="">Selected</option>
										<option value="layak-huni">Layak Huni</option>
										<option value="mudah-diakses">Mudah Diakses</option>
										<option value="langsung-berhubungan-dengan-jalan-raya">Langsung berhubungan dengan jalan raya</option>
									</select>
									<div class="form-control-feedback" style="display:none"><p class="text-danger" id="kondisi_jaminan"></p></div>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label >Latitude :</label> <a href="#" onclick="refreshcordinate()"> Refresh Cordinates</a>
									<input type="text" class="form-control" readonly="" name="latitude_jaminan">
									<div class="form-control-feedback" style="display:none"><p class="text-danger" id="latitude_jaminan"></p></div>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label>Longitude :</label> <a href="#" onclick="refreshcordinate()"> Refresh Cordinates</a>
									<input type="email" class="form-control" readonly="" name="longitude_jaminan">
									<div class="form-control-feedback" style="display:none"><p class="text-danger" id="longitude_jaminan"></p></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md">
								<div class="form-group">
									<input type="hidden" name="imageJaminan1">
									<button class="btn btn-info" type="button" id="foto-tempat-jaminan-1">Foto 1 <i class="icon-copy dw dw-photo-camera1"></i></button>
									<div class="form-control-feedback" style="display:none"><p class="text-danger" id="imageJaminan1"></p></div>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<input type="hidden" name="imageJaminan2">
									<button class="btn btn-info" type="button" id="foto-tempat-jaminan-2">Foto 1 <i class="icon-copy dw dw-photo-camera1"></i></button>
									<div class="form-control-feedback" style="display:none"><p class="text-danger" id="imageJaminan2"></p></div>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<input type="hidden" name="imageJaminan3">
									<button class="btn btn-info" type="button" id="foto-tempat-jaminan-3">Foto 1 <i class="icon-copy dw dw-photo-camera1"></i></button>
									<div class="form-control-feedback" style="display:none"><p class="text-danger" id="imageJaminan3"></p></div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="card-footer" id="button-form-jm">
					<button type="submit" class="btn btn-primary">Save Jaminan</button>
					<button type="button" class="btn btn-danger canceled-now-wizard" data-dismiss="modal">Closed Modal</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="card-footer" id="button-form-all">
	<button type="button" class="btn btn-primary save-all-tt-jm-form-data">Save All</button>
	<button type="button" class="btn btn-danger canceled-now-wizard" data-dismiss="modal">Closed Modal</button>
</div>
