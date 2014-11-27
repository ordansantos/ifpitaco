package br.edu.ifpb;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;

import br.edu.ifpb.ifpitaco_mobile.R;
import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.Toast;

public class CadastrarActivity extends Activity implements OnClickListener{
	 private EditText nome;
	 private EditText email;
	 private EditText senha;
	 private Button btCadastrar;
	 
	 @Override
		protected void onCreate(Bundle savedInstanceState) {
			super.onCreate(savedInstanceState);
			setContentView(R.layout.activity_cadastrar);
			
			nome = (EditText) findViewById(R.id.etNomeCadastro);
			email = (EditText) findViewById(R.id.etEmailCadastro);
			senha = (EditText) findViewById(R.id.etSenhaCadastro);
			btCadastrar = (Button) findViewById(R.id.btCadastrar);
			
			btCadastrar.setOnClickListener(this);
		}

	@Override
	public void onClick(View v) {
		new postLogin().execute();
	}
	
	//Post com AsyncTask
    private class postLogin extends AsyncTask<Void, Void, String> {
	 @Override
	 protected String doInBackground(Void... params) {
	                    
	           String url = "http://192.168.1.44/WebServer/postUsuario";
	           String content = null;
	                    
	           JSONObject object = new JSONObject();
	                    
	           try {
	        	   object.put("nm_usuario", nome.getText());
	        	   object.put("email", email.getText());
	        	   object.put("senha", senha.getText());
	   
	           } catch (JSONException e) {
	        	   e.printStackTrace();
	           }
	                    
	           HttpClient client = new DefaultHttpClient();  
	           HttpPost post = new HttpPost(url);
	           HttpResponse response = null;
	                    
	           post.setHeader("Content-type", "application/json");
	           post.setHeader("Accept", "application/json");
	                
	           try {
	        	   post.setEntity(new StringEntity(object.toString(), "UTF-8"));
	                            
	           } catch (UnsupportedEncodingException e) {
	        	   e.printStackTrace();
	           } catch (IOException e) {
	        	   e.printStackTrace();
	           }
	                
	           try {
	        	   response = client.execute(post);
	        	   content = EntityUtils.toString(response.getEntity());
	           } catch (IOException e) {
	               e.printStackTrace();
	           }
	                	
	           return content;
	    }

	            @SuppressLint("ShowToast")
	            @Override
	    protected void onPostExecute(String result) {
	            String aux = result.substring(2);
	        
	            
	            if (aux.equals("erro")) {
	            	Toast.makeText(getBaseContext(), "Erro no cadastro", Toast.LENGTH_SHORT)
					.show();
	            } else {
	            	Toast.makeText(getBaseContext(), "Cadastro efetuado com sucesso", Toast.LENGTH_SHORT)
					.show();
	            	Intent i = new Intent (getBaseContext(), LoginActivity.class);
	        		startActivity(i);
	            }
		
			}

	    }
	

}
