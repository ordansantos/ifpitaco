package br.edu.ifpb;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.Arrays;

import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
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
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

public class ProporActivity extends Activity implements OnClickListener {

	private Spinner SpEntidades;
	private ArrayAdapter<String> listAdapter;
	private Button BtEnviar;
	private EditText ETPropor;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_propor);
		SpEntidades = (Spinner) findViewById(R.id.SpEntidadesPropor);
		BtEnviar = (Button) findViewById(R.id.BtEnvProposta);
		ETPropor = (EditText) findViewById(R.id.ETPropor1);
		BtEnviar.setOnClickListener(this);
		
		String entidades[] = new String[]{"Infraestrutura", "Ensino", "Gestão"};
		
		ArrayList<String> entidadesList = new ArrayList<String>();
		entidadesList.addAll(Arrays.asList(entidades));
		
		listAdapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_dropdown_item, entidadesList); 
		
		SpEntidades.setAdapter(listAdapter);
		
	}

	@Override
	public void onClick(View v) {
		if (ETPropor.getText().length() == 0){
            Toast.makeText(getBaseContext(), "Preencha os campos!", Toast.LENGTH_SHORT).show();
		} else
            new postProposta().execute();
		
		Intent i = new Intent(this, PropostasActivity.class);
		startActivity(i);
	}
	
	
	//Post com AsyncTask
    private class postProposta extends AsyncTask<Void, Void, Void> {

    @Override
    protected Void doInBackground(Void... params) {
                    
                    String url = "http://179.180.4.184/WebService/postProposta";
  
                    
                    JSONObject object = new JSONObject();
                    
                    try {
                            object.put("comentario", ETPropor.getText());
                            object.put("usuario_id", "Teste_Android");
                            object.put("ramo_id", "ramo_test");
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
            
                    EditText comentario = (EditText) findViewById(R.id.ETPropor1);
                    comentario.setText("");
                    
            Toast.makeText(getBaseContext(), "Enviado!", Toast.LENGTH_SHORT).show();
    }
    }
	
}
