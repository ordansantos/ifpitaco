package br.edu.ifpb.asynctask;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONObject;

import android.os.AsyncTask;
import br.edu.ifpb.EndereçoServiço;
import br.edu.ifpb.entidades.Ramo;

public class RamosAsyncTask extends AsyncTask<Void, Void, List<Ramo>> {

	@Override
	protected List<Ramo> doInBackground(Void... params) {
		
		String url = EndereçoServiço.getEndereço()+"getRamos";

		List<Ramo> ramos = new ArrayList<Ramo>();
		
		
		try {

			HttpClient httpClient = new DefaultHttpClient();
			HttpResponse response = httpClient.execute(new HttpGet(url));

			String content = EntityUtils.toString(response.getEntity());

			content = content.substring(2); // retirando o /n/n

			JSONObject jsnobject = new JSONObject(content);

			JSONArray jsonArray = jsnobject.getJSONArray("ramos");

			for (int i = 0; i < jsonArray.length(); i++) {
				JSONObject explrObject = jsonArray.getJSONObject(i);
				String nomeRamo = explrObject.getString("nm_ramo");
				int id = explrObject.getInt("id_ramo");
				ramos.add(new Ramo(id, nomeRamo));
			}

		} catch (Exception e) {

		}

		return ramos;
	}
}