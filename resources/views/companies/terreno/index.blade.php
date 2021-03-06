@extends('layouts.principal')


@section('content')
<div class="row">
	<div class="col-md-6">
		<h2 class=font-weight-bold> Compañias &#127970; </h2>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-md-12">
		@include('flash::message')
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		@include('companies.terreno.table')
	</div>
</div>
<!-- Modal delete -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
		{!! Form::open(['route'=> ['companies.destroy'], 'method' => 'delete'] ) !!}
      <div class="modal-header">
        <h5 class="modal-title" id="deleteLabel">Eliminar Empresa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<select name="company" id="company" class="form-control">
							<option null selected disabled>Seleccione una empresa.</option>
							@foreach ($companies as $company)
							<option value="{{$company->id}}">{{$company->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
      </div>
      <div class="modal-footer">
				{!! Form::button('Eliminar', ['type' => 'submit', 'class' => 'btn btn-danger','onclick' => "return confirm('¿Seguro que desea eliminar?')"]) !!}
				{{-- {!! Form::submit('Eliminar', ['class' => 'btn btn-danger']) !!} --}}
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
			</div>
		{!! Form::close() !!}
    </div>
  </div>
</div>
<!-- FIN Modal delete -->
<!-- Modal create -->
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="createLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
		{!! Form::open(['route'=> ['companies.store'], 'method' => 'post'] ) !!}
      <div class="modal-header">
        <h5 class="modal-title" id="createLabel">Crear Empresa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						{!! Form::text('name',null, ['class'=> 'form-control', 'placeholder' => 'Nombre Empresa']) !!}
					</div>
				</div>
      </div>
      <div class="modal-footer">
				{!! Form::submit('Crear', ['class' => 'btn btn-success']) !!}
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
			</div>
		{!! Form::close() !!}
    </div>
  </div>
</div>
<!-- FIN Modal Create -->
@endsection
@section('script')
<script>
	$(document).ready( function () {
    $('#table_terreno').DataTable();
	});
</script>
@endsection

