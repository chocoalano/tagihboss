<table class="table table-striped">
	<thead>
		<tr>
			<th>Permission</th>
			<th>Guard</th>
			<th width="10">Access</th>
		</tr>
	</thead>
	<tbody>

		@foreach ($permission as $key => $value)
			<tr>
				<td>{{$value->name}}</td>
				<td>{{$value->guard_name}}</td>
				@if(!is_null($rolePermissions))
				<td><input type="checkbox" class="switch-btn" data-color="#0099ff" name="permission[]" value="{{ $value->id }}" {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}></td>
				@else
				<td><input type="checkbox" class="switch-btn" data-color="#0099ff" name="permission[]" value="{{ $value->id }}"></td>
				@endif
			</tr>
		@endforeach
	</tbody>
</table>