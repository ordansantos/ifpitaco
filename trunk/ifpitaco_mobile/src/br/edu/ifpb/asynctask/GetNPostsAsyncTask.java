package br.edu.ifpb.asynctask;

import java.util.ArrayList;

import org.apache.http.HttpResponse;

import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONObject;

import br.edu.ifpb.servico.HttpService;
import android.os.AsyncTask;

public class GetNPostsAsyncTask extends
		AsyncTask<Void, Void, ArrayList<String>> {

	@Override
	protected ArrayList<String> doInBackground(Void... params) {
		String content = null;
		ArrayList<String> list = new ArrayList<String>();
		int n = 5;

		try {

			HttpResponse response = HttpService
					.sendGETRequest("getNPosts/" + n);

			content = EntityUtils.toString(response.getEntity());
			content = content.substring(2); // retirando o /n/n

			JSONObject jsnobject = new JSONObject(content);

			JSONArray jsonArray = jsnobject.getJSONArray("posts");

			for (int i = 0; i < jsonArray.length(); i++) {
				JSONObject explrObject = jsonArray.getJSONObject(i);
				String nome_usuario = explrObject.getString("nm_usuario");
				String comentario = explrObject.getString("comentario");
				String dt_hr = explrObject.getString("data_hora");
				String nome_ramo = explrObject.getString("nm_ramo");
				list.add(nome_usuario + ": " + comentario + "\nSobre: "
						+ nome_ramo + " Data: " + dt_hr + ".\n");
			}
		} catch (Exception e) {

		}

		return list;
	}

	/*
	 * @Override protected void onPostExecute(Void result) {
	 * 
	 * ArrayAdapter<String> adapter = new ArrayAdapter<String>(
	 * getBaseContext(), android.R.layout.simple_list_item_1, myArrayList);
	 * 
	 * listview.setAdapter(adapter);
	 * 
	 * super.onPostExecute(result); }
	 */
}