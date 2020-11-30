@extends('layouts.menu')
@extends('plantilla')

<title>Gestión de turnos | Dengue</title>

@section('contenido')
<div class = "row">
<?php 
    if (empty($fecha = $_GET['f'])) {
        $fecha = date('Y-m-d');
    }
?>    
<div class = "col-sm-2" style = "margin-top:10px;">
    <input type = "date" class = "form-control" name = "fecha_turno" id = "fecha_turno" min = "<?php echo $fecha=date('Y-m-d'); ?>" value = "<?php echo $fecha=$_GET['f']; ?>">
</div>
<div class = "col-sm-9" style = "margin-top:10px;">
<?php
$ioscor = App\Models\paciente::join('pacientes_turnos', 'pacientes_turnos.documento', 'pacientes.documento')
->where('pacientes_turnos.fecha', $fecha)
->where('pacientes_turnos.para', 'dengue')
->where('pacientes.obra_social', 'IOSCOR')
->whereOr('pacientes.obra_social', 'ioscor')->get()->count();
?>
@if($cantidad_ioscor == $ioscor)
<center>
<div class="callout callout-danger">
    <h5>¡No asignar más turnos a pacientes con IOSCOR!</h5>
    <p>¡El límite de turnos permitidos fue alcanzado, intente con una fecha distinta!</p>
</div>
</center>
@endif
<center>
<div class="callout callout-info" id = "no_laboral" style = "display:none;">
    <h5>¡Día no laborable!</h5>
    <p>El laboratorio permanecerá cerrado el <?php echo $fecha_sel = date('d-m-Y', strtotime($fecha)); ?>, seleccione otra fecha para asignar un turno</p>
</div>
</center>
</div>
</div>
<div class = "row" id = "row_dengue">
@foreach($horarios as $horario)
<div class = "col-sm-4" style = "margin-top:10px;">
    <div class="card card-primary collapsed-card">
        <div class="card-header">
            <?php
                $cantidad = App\Models\pacientes_turno::where([['fecha', $fecha],['id_horario', $horario->id]])->get()->count();
            ?>
            <h3 class="card-title">{{$horario->horario}} - <?php echo $cantidad; ?> | {{$cantidad_turnos}}</h3>
            @if($cantidad < $cantidad_turnos)
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse" style = "display:'.$estado.'">
                <i class="fas fa-plus" style = "margin-top:10px;"></i></button>
            </div>
            @endif
        </div>
        <div class="card-body" style="display: none;">
            <div class = "row">
                <input type = "number" id = "id_usuario" name = "id_usuario" value = "{{Auth::user()->id}}" hidden>
                <input type = "number" id = "id_horario" name = "id_horario" value = "{{$horario->id_horario}}" hidden>
            <div class = "col-sm-5">
                <input type = "number" class = "form-control" id = "documento{{$horario->id_horario}}" placeholder="Documento" required>
            </div>
            <div class = "col-sm-7">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name = "p75" id="p75">
                    <label class="custom-control-label" for="p75">P75</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input ley{{$horario->id_horario}}" id="ley{{$horario->id_horario}}">
                    <label class="custom-control-label" for="ley{{$horario->id_horario}}">Ley 26743</label>
                </div>
            </div>
        </div>
        <div class = "row" style = "margin-top:5px;">
            <div class = "col-sm-12">
                <input type = "text" class = "form-control" id = "paciente{{$horario->id_horario}}"  placeholder="Paciente" required>
            </div>
        </div>
        <div class = "row" style = "margin-top:5px;">
            <div class = "col-sm-12">
                <input type = "text" class = "form-control" id = "domicilio{{$horario->id_horario}}" placeholder="Domicilio" required>
            </div>
        </div>
        <div class = "row" style = "margin-top:5px;">
            <div class = "col-sm-5">
                <input type = "text" class = "form-control" id = "telefono{{$horario->id_horario}}"  placeholder="Teléfono" required>
            </div>
            <div class = "col-sm-7">
                <input type = "date" class = "form-control" id = "fecha_nacimiento{{$horario->id_horario}}" placeholder="Fecha">
            </div>
        </div>
        <div class = "row" style = "margin-top:5px;">
            <div class = "col-sm-12">
                <input type = "text" class = "form-control" id = "obra_social{{$horario->id_horario}}" placeholder="Obra social" required>
            </div>
        </div>
        <div class = "row" style = "margin-top:5px;">
            <div class = "col-sm-12">
                <input type = "text" class = "form-control" id = "comentarios{{$horario->id_horario}}" placeholder="Comentarios">
            </div>
        </div>
        <center>
            <button class = "btn btn-success" id = "guardar{{$horario->id_horario}}" style = "margin-top:5px;">Guardar</button>
        </center>
        </div> 
        </div>
    </div>
@endforeach
</div>
@endsection