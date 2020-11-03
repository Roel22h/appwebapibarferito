<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Encryption\Encrypter;

use DB;

use App\Model\TUsuario;

class ControllerUsuario extends Controller
{
    public function actionLogIn(Request $request, Encrypter $encrypter)
    {
		$email = $request->input('email');
		$password = $request->input('password');
		
		$tUsuario=TUsuario::whereRaw('email=?', [$email])->first();

		if($tUsuario!=null)
		{
			if($encrypter->decrypt($tUsuario->contrasenia)===$password)
			{

				return response()->json(['correcto' => true, 'mensajeGlobal' => 'Operación realizada correctamente.', 'tUsuario' => $tUsuario]);
			}

			return response()->json(['error' => true, 'mensajeGlobal' => 'Contraseña incorrecta']);
		}

		return response()->json(['error' => true, 'mensajeGlobal' => 'Usuario no encontrado']);
	}

	public function actionInsertar(Request $request, Encrypter $encrypter)
    {

        try
        {
			DB::beginTransaction();
			
			$tUsuario=new TUsuario();

			$tUsuario->codigoUsuario=uniqid();
			$tUsuario->nombre=trim($request->input('txtNombres'));
			$tUsuario->apellidoPaterno=trim($request->input('txtApellidoPaterno'));
			$tUsuario->apellidoMaterno=trim($request->input('txtApellidoMaterno'));
			$tUsuario->email=trim($request->input('txtEmail'));
			$tUsuario->telefono=trim($request->input('txtTelefono'));
			$tUsuario->sexo=trim($request->input('txtSexo'));
			$tUsuario->contrasenia=$encrypter->encrypt($request->input('txtContrasenia'));
			$tUsuario->rol=$request->input('txtRol');
			$tUsuario->estado='Activo';
			
			$tUsuario->save();
			
			DB::commit();
			
			return response()->json(['correcto' => true, 'mensajeGlobal' => 'Operación realizada correctamente.']);
        }
        catch(\Exception $e)
        {
			DB::rollback();
			
			return response()->json(['error' => true, 'mensajeGlobal' => 'Algo salio mal']);
        }
    }
}
