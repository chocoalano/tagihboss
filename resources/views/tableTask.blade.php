@if($q == 'task_assigment')
<div class="row mb-3">
	<div class="col-md">
		<div class="left pt-4">
			<h4>Task Assigment</h4>
		</div>
	</div>
	<div class="col-md">
		<div class="d-flex float-right pt-3">
			{{ $task->links() }}
		</div>
	</div>
</div>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">Task Code</th>
				<th scope="col">No Rekening</th>
				<th scope="col">Nama</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($task as $key => $value)
			<tr>
				<th scope="row">{{ $value->task_code }}</th>
				<td><span class="badge badge-primary">{{ $value->no_rekening }}</span></td>
				<td>{{ ucfirst(strtolower($value->nama_nasabah)) }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endif
@if($q == 'profile')
<div class="timeline-month">
	<h5>August, 2020</h5>
</div>
<div class="profile-timeline-list">
	<ul>
		@foreach ($timeline as $k => $v)
		<li>
			<div class="date">{{ date('d/m/y', strtotime(substr($v->created_at,0,10))) }}</div>
			<div class="task-name">
				@if($v->event == 'access')
				<i class="icon-copy dw dw-key3"></i>
				@elseif($v->event == 'store')
				<i class="icon-copy dw dw-add-file1"></i>
				@elseif($v->event == 'show')
				<i class="icon-copy dw dw-search2"></i>
				@elseif($v->event == 'update')
				<i class="icon-copy dw dw-edit-file"></i>
				@elseif($v->event == 'delete')
				<i class="icon-copy dw dw-trash"></i>
				@elseif($v->event == 'login')
				<i class="icon-copy dw dw-login"></i>
				@elseif($v->event == 'logout')
				<i class="icon-copy dw dw-logout1"></i>
				@else
				<i class="icon-copy dw dw-file"></i>
				@endif
				{{ ucfirst(strtolower($v->event)) }}
			</div>
			<p>{{ ucfirst(strtolower($v->description)) }}</p>
			<div class="table-responsive">
				<table>
					<tr>
						<th>IP Public</th>
						<td>: {{ ucfirst(strtolower($v->ip_address)) }}</td>
					</tr>
					<tr>
						<th>Platform</th>
						<td>: {{ ucfirst(strtolower($v->platform)) }}</td>
					</tr>
					<tr>
						<th>Boot</th>
						<td>: {{ ucfirst(strtolower($v->boot)) }}</td>
					</tr>
					<tr>
						<th>In Apps</th>
						<td>: {{ ucfirst(strtolower($v->is_in_apps)) }}</td>
					</tr>
					<tr>
						<th>Device</th>
						<td>: {{ ucfirst(strtolower($v->device)) }}</td>
					</tr>
					<tr>
						<th>Browser</th>
						<td>: {{ ucfirst(strtolower($v->browser)) }}</td>
					</tr>
					<tr>
						<th>Engine</th>
						<td>: {{ ucfirst(strtolower($v->browser_engine)) }}</td>
					</tr>
					<tr>
						<th>Agent</th>
						<td>: {{ ucfirst(strtolower($v->agent)) }}</td>
					</tr>
				</table>
			</div>
			<div class="task-time">{{substr($v->created_at,10)}}</div>
		</li>
		@endforeach
	</ul>
</div>
{{ $timeline->links() }}
@endif