package br.edu.ifpb.activity;

import br.edu.ifpb.ifpitaco_mobile.R;
import br.edu.ifpb.servico.HttpService;
import android.app.Activity;
import android.os.Bundle;

import java.util.ArrayList;

import org.apache.http.HttpResponse;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONObject;

import android.os.AsyncTask;
import android.widget.ArrayAdapter;
import android.widget.ListView;

public class PropostasActivity extends Activity {
	private ListView listview;
	private ArrayList<String> myArrayList;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_propostas);

		listview = (ListView) findViewById(R.id.listPropostas);
		myArrayList = new ArrayList<String>();

		new GetProposta().execute();
	}

	// Get com AsyncTask
	private class GetProposta extends AsyncTask<Void, Void, Void> {

		@Override
		protected Void doInBackground(Void... params) {
			String content = null;

			try {
				
				HttpResponse response = HttpService.sendGETRequest("getPropostas");

				content = EntityUtils.toString(response.getEntity());
				content = content.substring(2); // retirando o /n/n

				JSONObject jsnobject = new JSONObject(content);

				JSONArray jsonArray = jsnobject.getJSONArray("propostas");

				for (int i = 0; i < jsonArray.length(); i++) {
					JSONObject explrObject = jsonArray.getJSONObject(i);
					String nome_usuario = explrObject.getString("nm_usuario");
					String comentario = explrObject.getString("comentario");
					String dt_hr = explrObject.getString("data_hora");
					String nome_ramo = explrObject.getString("nm_ramo");
					myArrayList.add("O usuário " + nome_usuario
							+ " propôs acerca do(a) " + nome_ramo
							+ "\nComentário: " + comentario + "\nData: " + dt_hr
							+ "\n");
				}
			} catch (Exception e) {

			}

			return null;
		}

		@Override
		protected void onPreExecute() {
		}

		@Override
		protected void onProgressUpdate(Void... values) {

		}

		@Override
		protected void onPostExecute(Void result) {

			ArrayAdapter<String> adapter = new ArrayAdapter<String>(
					getBaseContext(), android.R.layout.simple_list_item_1,
					myArrayList);

			listview.setAdapter(adapter);

			super.onPostExecute(result);
		}
	}

}
