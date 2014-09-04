package com.example.formulario;


import java.io.IOException;
import java.io.UnsupportedEncodingException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
import org.json.JSONObject;
import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class MainActivity extends Activity {
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		Toast.makeText(getBaseContext(), "http://179.180.149.74/", Toast.LENGTH_SHORT).show();
		Button bt = (Button) findViewById(R.id.button1);
		
		bt.setOnClickListener(new View.OnClickListener() {
		
			@Override
			public void onClick(View v) {
				
				EditText nome = (EditText) findViewById(R.id.nome);
				EditText comentario = (EditText) findViewById(R.id.comentario);
				
				if (nome.getText().length() == 0 || comentario.getText().length() == 0){
					Toast.makeText(getBaseContext(), "Preencha os campos!", Toast.LENGTH_SHORT).show();
				} else
					new postComentario().execute();
			}
		});
		
		Button bt2 = (Button) findViewById(R.id.button2);
		
		bt2.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				Intent i = new Intent(MainActivity.this, OpinioesActivity.class);
				startActivity(i);
			}
		});
	}
	
	//Post com AsyncTask
	private class postComentario extends AsyncTask<Void, Void, Void> {

		@Override
        protected Void doInBackground(Void... params) {
			
			String url = "http://179.180.149.74/WebServer/postComentario";
			
			EditText nome = (EditText) findViewById(R.id.nome);
			EditText comentario = (EditText) findViewById(R.id.comentario);
			
			JSONObject object = new JSONObject();
			
			try {
				object.put("comentario", comentario.getText());
				object.put("nome", nome.getText());
			} catch (JSONException e) {
				e.printStackTrace();
			}
			
			HttpClient client = new DefaultHttpClient();  
			HttpPost post = new HttpPost(url);
		    post.setHeader("Content-type", "application/json");
		    post.setHeader("Accept", "application/json");
		    
		    try {
		    	
				post.setEntity(new StringEntity(object.toString(), "UTF-8"));
				
			} catch (UnsupportedEncodingException e) {
				e.printStackTrace();
			}
		    
		    try {
				//HttpResponse response = client.execute(post);
		    	client.execute(post);
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		    
			return null;
        }

        @Override
        protected void onPreExecute() {}

        @Override
        protected void onProgressUpdate(Void... values) {}
        

		@SuppressLint("ShowToast")
		@Override
        protected void onPostExecute(Void result) {
        	
			EditText nome = (EditText) findViewById(R.id.nome);
			EditText comentario = (EditText) findViewById(R.id.comentario);
			nome.setText("");
			comentario.setText("");
			
        	Toast.makeText(getBaseContext(), "Enviado!", Toast.LENGTH_SHORT).show();
        }
    }
	
}
