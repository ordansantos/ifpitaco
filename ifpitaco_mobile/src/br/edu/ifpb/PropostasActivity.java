package br.edu.ifpb;

import br.edu.ifpb.ifpitaco_mobile.R;
import android.app.Activity;
import android.os.Bundle;
import java.util.ArrayList;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONObject;
import android.os.AsyncTask;
import android.os.Bundle;
import android.widget.ArrayAdapter;
import android.widget.ListView;

public class PropostasActivity extends Activity{
	private ListView listview;
    private ArrayList<String> myArrayList;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_propostas);
		
		listview = (ListView) findViewById(R.id.listPropostas);
        myArrayList = new ArrayList<String>();
        
        new getComentario().execute();
	}
	
	//Get com AsyncTask
    private class getComentario extends AsyncTask<Void, Void, Void> {

            @Override
    protected Void doInBackground(Void... params) {
                    String url = "http://179.180.149.74/WebServer/getComentario";
                    String content = null;
                    
                    try{
                            
                            HttpClient httpClient = new DefaultHttpClient();
                            HttpResponse response = httpClient.execute(new HttpGet(url));
                            
                            content = EntityUtils.toString(response.getEntity());
                            
                            
                            JSONObject jsnobject = new JSONObject(content);
                             
                            JSONArray jsonArray = jsnobject.getJSONArray("comentarios");
                             
                            for (int i = 0; i < jsonArray.length(); i++) {
                                JSONObject explrObject = jsonArray.getJSONObject(i);
                                String nome = explrObject.getString("nome");
                                String comentario = explrObject.getString("comentario");
                                myArrayList.add (nome + " : " + comentario);
                            }       

                    } catch(Exception e){
                            
                    }
                    
                    return null;
    }

    @Override
    protected void onPreExecute() {}

    @Override
    protected void onProgressUpdate(Void... values) {
            
    }
    
    @Override
    protected void onPostExecute(Void result) {
            
        ArrayAdapter<String> adapter = new ArrayAdapter<String>(getBaseContext(),
                android.R.layout.simple_list_item_1, android.R.id.text1, myArrayList);
            
        listview.setAdapter(adapter);
            
            super.onPostExecute(result);
    }
}
	
}
