<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\horarios_estudio;
use App\Models\horario;
use App\Models\config;
use App\Models\pacientes_turno;
use App\Models\valores_turno;
use App\Models\paciente;
use App\Models\obras_socials;
use App\Models\no_laborale;
use App\Models\practica;
use App\Models\turnos_practica;
use Illuminate\Support\Facades\Auth;

class ControladorTurnos extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function no_laborales(Request $request)
    {
        $fecha = $request->fecha;

        $cons_fecha = no_laborale::where('fecha', $fecha)->get()->count();

        return $cons_fecha;
    }

    public function dengue()
    {        
        $picked = true;
        $practicas = [];
        $obras_sociales = obras_socials::where('estado', 'H')->orderBy('obra_social')->get();
        $horarios = horario::join('horarios_estudios', 'horarios_estudios.id_horario', 'horarios.id_horario')
        ->where('horarios_estudios.estudio', 'dengue')
        ->orderBy('horarios.horario')
        ->get();

        $cantidad_turnos = config::get()->pluck('cant_turnos_gen')->first();
        $cantidad_ioscor = config::get()->pluck('cant_turnos_ioscor')->first();

        return view('turnos.dengue', compact('horarios', 'cantidad_turnos', 'cantidad_ioscor', 'obras_sociales', 'practicas', 'picked'));
    }

    public function exudado()
    {        
        $picked = true;
        $practicas = [];
        $obras_sociales = obras_socials::where('estado', 'H')->orderBy('obra_social')->get();
        $horarios = horario::join('horarios_estudios', 'horarios_estudios.id_horario', 'horarios.id_horario')
        ->where('horarios_estudios.estudio', 'exudado')
        ->orderBy('horarios.horario')
        ->get();

        $cantidad_turnos = config::get()->pluck('cant_turnos_gen')->first();
        $cantidad_ioscor = config::get()->pluck('cant_turnos_ioscor')->first();

        return view('turnos.exudado', compact('horarios', 'cantidad_turnos', 'cantidad_ioscor', 'obras_sociales', 'practicas', 'picked'));
    }

    public function espermograma()
    {        
        $picked = true;
        $practicas = [];
        $obras_sociales = obras_socials::where('estado', 'H')->orderBy('obra_social')->get();
        $horarios = horario::join('horarios_estudios', 'horarios_estudios.id_horario', 'horarios.id_horario')
        ->where('horarios_estudios.estudio', 'espermograma')
        ->orderBy('horarios.horario')
        ->get();

        $cantidad_turnos = config::get()->pluck('cant_turnos_esp')->first();
        $cantidad_ioscor = config::get()->pluck('cant_turnos_ioscor')->first();

        return view('turnos.espermograma', compact('horarios', 'cantidad_turnos', 'cantidad_ioscor', 'obras_sociales', 'practicas', 'picked'));
    }

    public function general(Request $request)
    {      
        $picked = true; 
        $fecha = $request->fecha;
        $practicas = [];
        $obras_sociales = obras_socials::where('estado', 'H')->orderBy('obra_social')->get();
        $horarios = horario::join('horarios_estudios', 'horarios_estudios.id_horario', 'horarios.id_horario')
        ->where('horarios_estudios.estudio', 'generales')
        ->orderBy('horarios.horario')
        ->get();

        $cantidad_turnos = config::get()->pluck('cant_turnos_gen')->first();
        $cantidad_ioscor = config::get()->pluck('cant_turnos_ioscor')->first();

        return view('turnos.generales-livewire', compact('picked', 'practicas', 'horarios', 'cantidad_turnos', 'cantidad_ioscor', 'fecha', 'obras_sociales', 'picked'));
    }

    public function citogenetica()
    {       
        $picked = true; 
        $practicas = [];
        $obras_sociales = obras_socials::where('estado', 'H')->orderBy('obra_social')->get();
        $horarios = horario::join('horarios_estudios', 'horarios_estudios.id_horario', 'horarios.id_horario')
        ->where('horarios_estudios.estudio', 'citogenetica')
        ->orderBy('horarios.horario')
        ->get();

        $cantidad_turnos = config::get()->pluck('cant_turnos_gen')->first();
        $cantidad_ioscor = config::get()->pluck('cant_turnos_ioscor')->first();

        return view('turnos.citogenetica', compact('horarios', 'cantidad_turnos', 'cantidad_ioscor', 'obras_sociales', 'practicas', 'picked'));
    }

    public function busco_paciente(Request $request)
    {
        $paciente = paciente::where('documento', $request->documento)->get()->pluck('paciente')->first();
        $fecha_nac = paciente::where('documento', $request->documento)->get()->pluck('fecha_nac')->first();
        $domicilio = paciente::where('documento', $request->documento)->get()->pluck('domicilio')->first();
        $telefono = paciente::where('documento', $request->documento)->get()->pluck('telefono')->first();
        
        return $paciente. ';' .$fecha_nac. ';' .$domicilio. ';' .$telefono;
    }

    public function genero_id_turno(Request $request)
    {
        $valor = valores_turno::orderBy('valor', 'DESC')->get()->pluck('valor')->first();

        if (empty($valor)) {
           $valor = 1;
           $inserto = valores_turno::create(['valor' => $valor]);
        } else {
           $valor = $valor + 1;
           $actualizo = valores_turno::where('id', 1)->update(['valor' => $valor]);
        }
 
        $id_usuario = Auth::user()->id;

        if (strlen($valor) == 1) {
           $valor = "00000" .$valor;
        } elseif (strlen($valor) == 2) {
            $valor = "0000" .$valor;
        } elseif (strlen($valor) == 3) {
            $valor = "000" .$valor;
        }   elseif (strlen($valor) == 4) {
            $valor = "00" .$valor;
        } elseif (strlen($valor) == 5) {
            $valor = "0" .$valor;
        } elseif (strlen($valor) >= 6) {
            $valor = $valor;
        }

        return $id_usuario. '-' .$valor;
    }

    public function busco_nomenclador(Request $request)
    {
        $obra_social_id = $request->obra_social_id;
        $nomenclador = obras_socials::where('id', $obra_social_id)->get()->pluck('nomenclador')->first();

        return $nomenclador;
    }

    public function busco_codigo(Request $request)
    {
        $codigo = $request->codigo;
        $nomenclador = $request->nomenclador;

        $id_practica = practica::where('codigo', $codigo)->where('nomenclador', $nomenclador)
        ->where('estado', 'H')->get()->pluck('id_practica')->first();

        return $id_practica;
    }

    public function busco_practica(Request $request)
    {   
        $practicas = practica::where('practica', 'LIKE', '%' .$request->practica. '%')
        ->where('nomenclador', $request->nomenclador)->where('estado', 'H')->get();

        return response(json_encode($practicas), 200)->header('Content-type', 'text/plain');
    }

    public function turno_practicas(Request $request)
    {
        $guardo_practica = turnos_practica::create([
            'id_turno' => $request->id_turno,
            'id_obra_social' => $request->id_obra_social,
            'id_practica' => $request->id_practica,
            'cantidad' => 1
        ]);
    }

    public function muestro_practicas(Request $request)
    {
        $practicas = turnos_practica::join('practicas', 'practicas.id_practica', 'turnos_practicas.id_practica')
        ->select('turnos_practicas.id_practica', 'practicas.codigo', 'practicas.practica')
        ->where('turnos_practicas.id_turno', $request->id_turno)
        ->where('practicas.nomenclador', $request->nomenclador)->orderBy('practicas.codigo')
        ->get();

        return response(json_encode($practicas), 200)->header('Content-type', 'text/plain');
    }

    public function elimino_practica(Request $request)
    {
        $eliminar = turnos_practica::where('id_turno', $request->id_turno)->where('id_practica', $request->id_practica)->delete();
    }

    public function guardo_turno(Request $request)
    {
        //Array con la cantidad de turnos disponibles
        $cons_turnos = config::get()->pluck('cant_turnos_gen')->first();

        for ($i=1; $i < $cons_turnos + 1; $i++) { 
            $array_turnos[] = $i;
        }    
        
        //Letra según el horario seleccionado
        $letra = horario::where('id_horario', $request->id_horario)->get()->pluck('letra')->first();
        
        //Array con los turnos ya ocupados
        $cons_ocupados = pacientes_turno::join('horarios', 'horarios.id_horario', 'pacientes_turnos.id_horario')
        ->select('pacientes_turnos.id')
        ->where('horarios.id_horario', $request->id_horario)
        ->where('pacientes_turnos.fecha', $request->fecha_turno)
        ->get();
        
        foreach ($cons_ocupados as $ocupados) {
            $array_ocupados[] = $ocupados['id'];
        }

        //Si el array de ocupados esta vacío lo ponemos en 0
        if (empty($array_ocupados)) {
            $array_ocupados = array("0");
        }
        
        //Sacamos la diferencia entre los dos arrays
        $array_libres = array_diff($array_turnos, $array_ocupados);
        
        //Le pasamos el primer valor del array con los turnos libres
        if (reset($array_libres) == 0) {
            $id_num = pacientes_turno::where('fecha', $request->fecha_turno)
            ->where('id_horario', $request->id_horario)->orderBy('id', 'DESC')->get()->pluck('id')->first() + 1 ;
        } else {
            $id_num = reset($array_libres);
        }
        
        //Verificamos valores vacios
        //Domicilio
        if (empty($request->domicilio)) {
            $domicilio = "CTES";
        } else {
            $domicilio = $request->domicilio;
        }
        //Teléfono
        if (empty($request->telefono)) {
            $telefono = 0;
        } else {
            $telefono = $request->telefono;
        }
        //Fecha de nacimiento
        if (empty($request->fecha_nacimiento)) {
            $fecha_de_nacimiento = date('Y-m-d');    
        } else {
            $fecha_de_nacimiento = $request->fecha_nacimiento;
        }

        //Verificamos si es para p75
        if (empty($request->p75)) {
            $para = $request->para;
        } else {
            $para = 'P75';
        }

        $fecha_hora = date('Y-m-d H:m:s');

        $cantidad = paciente::where('documento', $request->documento)->get()->count();

        if (empty($cantidad)) {
            $guardo_paciente = paciente::create([
                'documento' => $request->documento,
                'paciente' => $request->paciente,
                'fecha_nac' => $fecha_de_nacimiento,
                'domicilio' => $domicilio,
                'telefono' => $telefono,
                'correo' => '-',
                'obra_social_id' => $request->obra_social_id
            ]);

            $guardo_turno = pacientes_turno::create([
                'id_turno' => $request->id_turno,
                'id' => $id_num,
                'letra' => $letra,
                'fecha' => $request->fecha_turno,
                'id_horario' => $request->id_horario,
                'documento' => $request->documento,
                'id_usuario' => $request->id_usuario,
                'fecha_hora' => $fecha_hora,
                'para' => $para,
                'asistio' => 'no',
                'comentarios' => $request->comentarios
            ]);

            if (($guardo_paciente)&&($guardo_turno)) {
                echo "Correcto";
            }

        } else {
            $actualizo_paciente = paciente::where('documento', $request->documento)->update([
                'documento' => $request->documento,
                'paciente' => $request->paciente,
                'fecha_nac' => $fecha_de_nacimiento,
                'domicilio' => $request->domicilio,
                'telefono' => $request->telefono,
                'correo' => '-',
                'obra_social_id' => $request->obra_social_id
            ]);

            $guardo_turno = pacientes_turno::create([
                'id_turno' => $request->id_turno,
                'id' => $id_num,
                'letra' => $letra,
                'fecha' => $request->fecha_turno,
                'id_horario' => $request->id_horario,
                'documento' => $request->documento,
                'id_usuario' => $request->id_usuario,
                'fecha_hora' => $fecha_hora,
                'para' => $para,
                'asistio' => 'no',
                'comentarios' => $request->comentarios
            ]);

            if (($actualizo_paciente)&&($guardo_turno)) {
                echo "Correcto";
            }
        }
  
    }

    public function comprobante_turno($fecha, $id, $documento, $paciente, $id_turno)
    {
        $fecha = $fecha;
        $id_horario = $id;
        $documento = $documento;
        $paciente = $paciente;
        $id_turno = $id_turno;

        return view('impresiones.comprobante_turno', compact('fecha', 'id_horario', 'documento', 'paciente', 'id_turno'));
    }

    public function rechazo_turno()
    {
        return view('turnos.rechazo');
    }

    public function consultas()
    {
        return view('demanda.consultas');
    }
}
