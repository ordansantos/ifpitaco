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
import android.widget.Toast;

public class PropostasActivity extends Activity{
	private ListView listview;
    private ArrayList<String> myArrayList;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_propostas);
		
		listview = (ListView) findViewById(R.id.listPropostas);
        myArrayList = new ArrayList<String>();
        
        new getProposta().execute();
	}
	
	//Get com AsyncTask
    private class getProposta extends AsyncTask<Void, Void, Void> {

            @Override
    protected Void doInBackground(Void... params) {
                    String url = "http://179.180.4.184/WebService/getProposta";
                    String content = null;
                    
                    try{
                            
                            HttpClient httpClient = new DefaultHttpClient();
                            HttpResponse response = httpClient.execute(new HttpGet(url));
                            
                            content = EntityUtils.toString(response.getEntity());
                            
                            
                            JSONObject jsnobject = new JSONObject(content);
                             
                            JSONArray jsonArray = jsnobject.getJSONArray("propostas");
                             
                            for (int i = 0; i < jsonArray.length(); i++) {
                                JSONObject explrObject = jsonArray.getJSONObject(i);
                                String nome_usuario = explrObject.getString("nm_usuario");
                                String comentario = explrObject.getString("comentario");
                                String dt_hr = explrObject.getString("data_hora");
                                String nome_ramo = explrObject.getString("nm_ramo");
                                myArrayList.add (nome_usuario + " propôs acerca de " + nome_ramo + ":"+ comentario);
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
                android.R.layout.simple_list_item_1, myArrayList);
            
        listview.setAdapter(adapter);
            
          super.onPostExecute(result);
    }
}
	
}
