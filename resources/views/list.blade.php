<?php phpinfo(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stack Overflow Questions</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/list.css') }}">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Stack Overflow Questions</h1>
        <div class="row">
        	<div class="col-sm-12 col-md-6 col-lg6 offset-md-3 offset-lg-3">
        		<h2 class="text-center">Filtros</h2>
        	</div>
        	<div class="col-sm-12 col-md-6 col-lg6 offset-md-3 offset-lg-3">
		        <form id="filters" method="POST" action="{{ url('/') }}">
		        	@csrf
		            <div class="form-group">
		                <label for="tagged">Tagged*</label>
		                <input type="text" class="form-control" id="tagged" name="tagged" value="{{ old('tagged', $formData['tagged']) }}" required>
		            </div>
		            <div class="form-group">
		                <label for="fromdate">Desde</label>
		                <input type="date" class="form-control" id="fromdate" name="fromdate" value="{{ old('fromdate', $formData['fromdate']) }}"> 
		            </div>
		            <div class="form-group">
		                <label for="todate">Hasta</label>
		                <input type="date" class="form-control" id="todate" name="todate" value="{{ old('todate', $formData['todate']) }}">
		            </div>
					<button type="submit" name="action" value="search" id="search" class="btn btn-primary">Buscar</button>
            		<button type="submit" name="action" value="clean" id="clean" class="btn btn-danger" disabled>Limpiar</button>
      		    </form>
		    </div>
	    </div>
	    <div class="row">
        	<div class="col-sm-12 col-md-6 col-lg6 offset-md-3 offset-lg-3">
        		<form id="last_search" 0 method="POST" action="{{ url('/') }}">
		        	@csrf
		            <div class="form-group">
		                <input type="text" class="form-control" id="tagged" name="tagged" value="{{ old('tagged', $formData['tagged']) }}" required readonly>
		            </div>
		            <div class="form-group">
		                <input type="date" class="form-control" id="fromdate" name="fromdate" value="{{ old('fromdate', $formData['fromdate']) }}" readonly> 
		            </div>
		            <div class="form-group">
		                <input type="date" class="form-control" id="todate" name="todate" value="{{ old('todate', $formData['todate']) }}" readonly>
		            </div>
            		<button type="submit" name="action" value="save" id="save" class="btn btn-secondary" disabled>Guardar esta búsqueda</button>
      		    </form>
	        </div>
	    </div>
	    <div class="row">
        	<div class="col-sm-12 col-md-6 col-lg6 offset-md-3 offset-lg-3">
			     @if(session('success'))
		            <div class="alert alert-success mt-3">
		                {{ session('success') }}
		            </div>
		        @endif

		        @if(session('error'))
		            <div class="alert alert-danger mt-3">
		                {{ session('error') }}
		            </div>
		        @endif
	        </div>
	    </div>
	    <div class="row">
	    	<div class="col-sm-12 col-md-6 col-lg6 offset-md-3 offset-lg-3">
        		<h2 class="text-center">Búsqueda actual</h2>
        	</div>
        	<div class="col-sm-12 col-md-12 col-lg12">
		        <table id="list" class="table table-striped table-bordered" style="width:100%">
		            <thead>
		                <tr>
		                    <th>Title</th>
		                    <th>Link</th>
		                    <th>Creation Date</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach(session('questions') as $question)
		                    <tr>
		                        <td>{{ $question['title'] }}</td>
		                        <td><a href="{{ $question['link'] }}" target="_blank">View Question</a></td>
		                        <td>{{ date('Y-m-d H:i:s', $question['creation_date']) }}</td>
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
	        </div>
	    </div>
	    <div class="row">
	    	<div class="col-sm-12 col-md-6 col-lg6 offset-md-3 offset-lg-3">
        		<h2 class="text-center">Búsquedas guardadas</h2>
        	</div>
	    	<div class="col-sm-12 col-md-12 col-lg12">
				<table id="searches" class="table table-striped">
		            <thead>
		                <tr>
		                    <th>ID</th>
		                    <th>Tag</th>
		                    <th>Desde</th>
		                    <th>Hasta</th>
		                    <th>Ver</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach ($searches as $search)
		                    <tr>
		                        <td>{{ $search->id }}</td>
		                        <td>{{ $search->tag }}</td>
		                        <td>{{ $search->fromdate }}</td>
		                        <td>{{ $search->todate }}</td>
		                        <td><button data-id="{{ $search->id }}" class="btn btn-primary view-btn">Ver</button></td>
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
			</div>
	    </div>
    </div>
    <div class="modal fade" id="questionsModal" tabindex="-1" role="dialog" aria-labelledby="questionsModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="questionsModalLabel">Preguntas</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body">
	                <table id="questionsTable" class="table table-striped">
	                    <thead>
	                        <tr>
	                            <th>ID</th>
	                            <th>Título</th>
	                            <th>Enlace</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    </tbody>
	                </table>
	            </div>
	        </div>
	    </div>
	</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#list').DataTable();
            $('#searches').DataTable();
            checkFormFields();
            $('#filters input').on('input', function() {
                checkFormFields();
            });
            if($('#last_search #tagged').val() !== '') {
            	$('#save').prop('disabled', false);
            }
            popUp();
        });
        function checkFormFields() {
        	var hasValue = false;
            $('#filters input[type="text"], form input[type="date"]').each(function() {
                if ($(this).val().trim() !== '') {
                    hasValue = true;
                    return false;
                }
            });
            $('#clean').prop('disabled', !hasValue);
        }
        function popUp() {
        	$('#searches').on('click', '.view-btn', function() {
	        	var searchId = $(this).data('id');
		        $.ajax({
		            url: '/searches/' + searchId + '/questions',
		            method: 'GET',
		            success: function(data) {
		                var $tableBody = $('#questionsTable tbody');
		                $tableBody.empty();
		                $.each(data, function(index, question) {
		                    $tableBody.append(
		                        '<tr>' +
		                        '<td>' + question.question_id + '</td>' +
		                        '<td>' + question.title + '</td>' +
		                        '<td><a href="' + question.link + '" target="_blank">Ver pregunta</a></td>' +
		                        '</tr>'
		                    );
		                });

		                $('#questionsModal').modal('show');
		            },
		            error: function() {
		                alert('Error al obtener las preguntas.');
		            }
		        });
	        });
	    }
    </script>
</body>
</html>