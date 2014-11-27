package br.edu.ifpb;

import java.io.IOException;
import java.io.UnsupportedEncodingException;

import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;
import br.edu.ifpb.ifpitaco_mobile.R;

public class LoginActivity extends Activity implements OnClickListener{
	
	private EditText etEmail;
	private EditText etSenha;
	private Button btEntrar;
	private TextView tvCadastra;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);
		
		etEmail = (EditText) findViewById(R.id.ETEmailUsuario);
		etSenha = (EditText) findViewById(R.id.ETSenhaUsuario);
		btEntrar = (Button) findViewById(R.id.BtEntrar);
		tvCadastra = (TextView) findViewById(R.id.cadastroTextView);
		
		btEntrar.setOnClickListener(this);
		tvCadastra.setOnClickListener(this);
		
		
	}

	@Override
	public void onClick(View v) {
		switch (v.getId()) {
			case R.id.BtEntrar:
				new postLogin().execute();
				break;
			case R.id.cadastroTextView:
				Intent i = new Intent (getBaseContext(), CadastrarActivity.class);
        		startActivity(i);
		}
	}
	
	//Post com AsyncTask
    private class postLogin extends AsyncTask<Void, Void, String> {

    @Override
    protected String doInBackground(Void... params) {
                    
            		String url = "http://192.168.1.44/WebServer/postLogin";
                    String content = null;
                    
                    JSONObject object = new JSONObject();
                    
                    try {
                            object.put("email", etEmail.getText());
                            object.put("senha", etSenha.getText());
   
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
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
                
                	try {
                		response = client.execute(post);
                		content = EntityUtils.toString(response.getEntity());
                    } catch (IOException e) {
                            // TODO Auto-generated catch block
                            e.printStackTrace();
                    }
                	
                    return content;
    }

            @SuppressLint("ShowToast")
            @Override
    protected void onPostExecute(String result) {
            String aux = result.substring(2);
            int id = Integer.valueOf(aux);
                        	
            if (id == 0) {
            	Toast.makeText(getBaseContext(), "Senha ou e-mail incorretos", Toast.LENGTH_SHORT)
				.show();
            } else {
            	
            	Toast.makeText(getBaseContext(), "Bem Vindo ao IFPitaco", Toast.LENGTH_SHORT)
				.show();
            	Intent i = new Intent (getBaseContext(), FuncoesListActivity.class);
        		startActivity(i);
            }
	
		}

    }

}
