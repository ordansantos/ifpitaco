package br.edu.ifpb.asynctask;

import java.io.IOException;
import java.util.HashMap;

import org.apache.http.HttpResponse;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;

import android.os.AsyncTask;
import br.edu.ifpb.entidades.Usuario;
import br.edu.ifpb.servico.HttpService;

public class PerfilAsyncTask extends AsyncTask<Void, Void, HashMap<String,String>> {

		@Override
		protected HashMap<String, String> doInBackground(Void... params) {
			String content = null;
			HashMap<String,String> dados = new HashMap<String, String>();
			int id = Usuario.getId();

			try {

				HttpResponse response = HttpService.sendGETRequest("getUsuarioById/"
						+ id);

				content = EntityUtils.toString(response.getEntity());
				//content = content.substring(3); // retirando o /n/n
				content = (String) content.subSequence(3, content.length());

				JSONObject jsnobject = new JSONObject(content);
				//jsnobject = jsnobject.getJSONObject("");
				String nome_usuario = jsnobject.getString("nm_usuario");
				String usuario_tipo = jsnobject.getString("usuario_tipo");
				String curso = jsnobject.getString("curso");
				String ano_periodo = jsnobject.getString("ano_periodo");
				String grau_academico = jsnobject.getString("grau_academico");
				
				dados.put("nome", nome_usuario);
				dados.put("tipo", usuario_tipo);
				dados.put("curso", curso);
				dados.put("ano", ano_periodo);
				dados.put("grauAcademico", grau_academico);

			} catch (IOException e) {
				e.printStackTrace();
			} catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}

			return dados;
		}


		
	}