<div class="col-md-4">
	Duracion evento:
	{!!number_format((int)(strtotime($event->enddate)-strtotime($event->initdate))/60/60/24)!!} dia(s)
</div>
<div class="col-md-4">
	Fecha inicio: {!! $event->InitDateF!!}
</div>
<div class="col-md-4">
	Fecha Termino: {!! $event->EndDateF!!}
</div>
@if($event->contents->count()==0)
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
@else
<div class="col-md-12 my-2">
	<div class="table table-responsive">
		<table class="table">
			<thead class="thead-dark">
				<tr>
					<th>Nombre del contenido</th>
					<th>Tamaño (mb)</th>
					<th>Resolucion</th>
					<th colspan="1">Asignar Pantalla</th>
				</tr>
			</thead>

			<tbody>
				@foreach($event->contents as $content)
				<tr>
					<td>{!! $content->name !!}</td>
					<td>{!! $content->SizeMB !!}</td>
					<td>{!! $content->Resolution !!}</td>
					<td>
						{!! Form::open(['route' => ['contents.destroy', $content->id], 'method' => 'delete', 'id'=>'form']) !!}
						<!--
									<div class='btn-group'>
										<a href="{!! route('contents.edit', [$content->id]) !!}" class='btn btn-warning btn-xs'><i
												class="fas fa-edit"></i></a>
										{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger
										btn-xs', 'onclick' => "return confirm('Estas seguro?')"]) !!} -->
						{{-- <a href="{{route('screens.AssignContent',$content->id) }}" class='btn btn-primary btn-xs'><i
								class="fas fa-desktop"></i></a> --}}
						<a href="{{route('events.assignations',[$event, $content]) }}" class='btn btn-primary btn-xs'><i
								class="fas fa-desktop"></i></a>
						<a href="{{route('contents.download',$content) }}" class='btn btn-success btn-xs'><i
								class="fa fa-download"></i></a>

						{!! Form::close() !!}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif

@section('script')
<script>
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
