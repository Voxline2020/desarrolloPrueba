@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-8">
		<h2> Evento {{ $event->name }} </h2>
	</div>
	<div class="col-md-8">
		@include('flash::message')
	</div>
	<div class="btn-group">
		<div class="col-md-4">
			<a class="btn btn-warning" data-toggle="collapse" href="#Editar" role="button" aria-expanded="false"
				aria-controls="collapseExample">
				Editar
			</a>
		</div>
		<div class="col-md-4">
			<a class="btn btn-primary" data-toggle="collapse" href="#AgregarContenido" role="button" aria-expanded="false"
				aria-controls="collapseExample">
				Agregar
			</a>
		</div>
		<div class="col-md-3">
			<a href="{!! route('companies.events.index', $event->company) !!}" class="btn btn-info">Atras</a>
		</div>
	</div>

</div>

<div class="row my-lg-4 my-md-4 my-sm-1">
	@include('events._show_fields')
</div>
{{-- collapse agregar contenido --}}
<div class="collapse" id="AgregarContenido">
	<div class="col-md-12">
		<table class="table table-hover">
			<thead class="thead-dark">
				<div class="panel-body text-right">
					{!! Form::open(['route'=> ['events.fileStore'], 'method' => 'POST', 'files'=>'true', 'id' =>
					'my-dropzone' , 'class' => 'dropzone my-2'] ) !!}
					<button type="submit" class="btn btn-success" id="submit">Guardar Contenido</button>
					{!! Form::close() !!}
				</div>
			</thead>
		</table>
	</div>
</div>
{{-- Fin collapse agregar contenido --}}

{{-- Collapse editar --}}
<div class="collapse" id="Editar">
	{!! Form::model($event, ['route' => ['events.update', $event->id], 'method' => 'patch', 'id' => 'editform']) !!}

	<div class="col-md-12">
		<hr>
		<h2>Editar evento</h2>
		<div class="form-group col-sm-3">
			{!! Form::label('name', 'Nombre del evento:') !!}
			{!! Form::text('name', $event->name, ['class' => 'form-control', 'required']) !!}
		</div>
		<div class="form-group col-sm-3">
			{!! Form::label('initdate', 'Fecha de inicio:') !!}
			<div class="input-group date" id="initdate" data-target-input="nearest">
				<input type="text" name="initdate" class="form-control datetimepicker-input" data-target="#initdate"
					value="{!! \Carbon\Carbon::parse($event->initdate)->format('d-m-Y H:i') !!}" required />
				<div class="input-group-append" data-target="#initdate" data-toggle="datetimepicker">
					<div class="input-group-text"><i class="fa fa-calendar"></i></div>
				</div>
			</div>
		</div>
		<div class="form-group col-sm-3">
			{!! Form::label('enddate', 'Fecha de termino:') !!}
			<div class="input-group date" id="enddate" data-target-input="nearest">
				<input type="text" name="enddate" class="form-control datetimepicker-input" data-target="#enddate"
					value="{!! \Carbon\Carbon::parse($event->enddate)->format('d-m-Y H:i') !!}" required />
				<div class="input-group-append" data-target="#enddate" data-toggle="datetimepicker">
					<div class="input-group-text"><i class="fa fa-calendar"></i></div>
				</div>
			</div>
		</div>
	</div>
	<!-- Submit Field -->
	<div class="form-group col-sm-12">
		{!! Form::hidden('company_id', $event->company_id) !!}
		{!! Form::submit('Guardar', ['class' => 'btn btn-primary','id' => 'editformButton']) !!}
	</div>
	{!! Form::close() !!}
</div>

{{-- Fin collapse editar --}}
@endsection
@section('script')
<script>
	$(function () {
			$('#initdate').datetimepicker({
				icons: {
						time: 'fas fa-clock',
						date: 'fas fa-calendar',
						up: 'fas fa-arrow-up',
						down: 'fas fa-arrow-down',
						previous: 'fas fa-chevron-left',
						next: 'fas fa-chevron-right',
						today: 'fas fa-calendar-check-o',
						clear: 'fas fa-trash',
						close: 'fas fa-times'
				},
				focusOnShow: true,
				allowInputToggle: true,
				locale: "es",
			});
			$('#enddate').datetimepicker({
				icons: {
						time: 'fas fa-clock',
						date: 'fas fa-calendar',
						up: 'fas fa-arrow-up',
						down: 'fas fa-arrow-down',
						previous: 'fas fa-chevron-left',
						next: 'fas fa-chevron-right',
						today: 'fas fa-calendar-check-o',
						clear: 'fas fa-trash',
						close: 'fas fa-times'
				},
				focusOnShow: true,
				allowInputToggle: true,
				locale: "es",
				useCurrent: false,
			});

		$("#initdate").on("change.datetimepicker", function (e) {
				$('#enddate').datetimepicker('minDate', e.date);
		});
		$("#enddate").on("change.datetimepicker", function (e) {
				$('#initdate').datetimepicker('maxDate', e.date);
		});
	});
	Dropzone.options.myDropzone = {
		acceptedFiles: '.mp4',
		autoDiscover: false,
		autoProcessQueue: false,
		uploadMultiple: true,
		maxFilezise: 500,
		maxFiles: 25,
		parallelUploads: 5,
		upload_max_filesize: 10000,
		addRemoveLinks: true,
		dictDefaultMessage: "Suba los archivos aqui",
		headers: {
			'X-CSRF-TOKEN': "{{ csrf_token() }}"
		},
		init: function() {
				var submitBtn = document.querySelector("#submit");
				myDropzone = this;
				submitBtn.addEventListener("click", function(e){
						// e.preventDefault();
						// e.stopPropagation();
						myDropzone.processQueue();

				});
				this.on("addedfile", function(file) {

				});
				this.on("complete", function(file) {
						myDropzone.removeFile(file);
						if(myDropzone.files.length==0){
								location.reload();
						}
				});

				this.on("success",
						myDropzone.processQueue.bind(myDropzone)
				);

				this.on('sending', function(file, xhr, formData) {
						// Append all form inputs to the formData Dropzone will POST
						var data = $('#my-dropzone').serializeArray();
						formData.append("event_id",'{{ $event->id }}');

						$.each(data, function(key, el) {
							formData.append(el.name, el.value);

						});

				});

		},
	};
</script>
@endsection
