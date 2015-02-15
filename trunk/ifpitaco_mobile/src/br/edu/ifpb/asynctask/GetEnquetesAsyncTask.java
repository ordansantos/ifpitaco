package br.edu.ifpb.asynctask;

import java.io.IOException;
import java.util.ArrayList;

import org.apache.http.HttpResponse;

import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import br.edu.ifpb.servico.HttpService;
import android.os.AsyncTask;

public class GetEnquetesAsyncTask extends
		AsyncTask<Void, Void, ArrayList<String>> {

	@Override
	protected ArrayList<String> doInBackground(Void... params) {
		String content = null;
		ArrayList<String> list = new ArrayList<String>();

		try {

			HttpResponse response = HttpService
					.sendGETRequest("getEnqueteIds/");

			content = EntityUtils.toString(response.getEntity());
			content = content.substring(2); // retirando o /n/n

			JSONObject jsnobject = new JSONObject(content);

			JSONArray jsonArray = jsnobject.getJSONArray("ids");

			for (int i = 0; i < jsonArray.length(); i++) {
				JSONObject explrObject = jsonArray.getJSONObject(i);
				response = HttpService.sendGETRequest("getEnquete/"
						+ explrObject.getInt("id_enquete"));

				content = EntityUtils.toString(response.getEntity());
				content = (String) content.subSequence(3, content.length());

				JSONObject jsnobject2 = new JSONObject(content);

				String titulo = jsnobject2.getString("titulo");
				String dt_hr = jsnobject2.getString("data_hora");
				String autor = jsnobject2.getString("nm_usuario");

				String texto = "Enquete: " + titulo + "\n";

				for (int j = 1; j <= jsnobject2.getInt("qtd_opt"); j++) {
					texto = texto + j + ". " + jsnobject2.getString("opt_" + j)
							+ "  Votos: "
							+ jsnobject2.getString("qtd_opt_" + j) + ".\n";
				}

				texto = texto + "Por: " + autor + " Data: " + dt_hr + "\n";

				list.add(texto);
			}

		} catch (IOException e) {
			e.printStackTrace();
		} catch (JSONException e) {
			e.printStackTrace();
		}

		return list;
	}

}