package br.edu.ifpb.entidades;

import java.io.IOException;

import org.apache.http.HttpResponse;
import org.apache.http.ParseException;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import br.edu.ifpb.EndereçoServiço;
import android.os.AsyncTask;


public class Usuario {
	private static String nome;
	private static int id;
	
	public static String getNome() {
		return nome;
	}

	public static void setNome(String n) {
		nome = n;
	}

	public static int getId() {
		return id;
	}

	public static void setId(int id) {
		Usuario.id = id;
	}
	
	public void setNomeUsuario () {
		new GetNomeById().execute();
	}
	//Get com AsyncTask
    private class GetNomeById extends AsyncTask<Void, Void, String> {

            @Override
    protected String doInBackground(Void... params) {
                    String url = EndereçoServiço.getEndereço()+"getNomeById/"+id;
                    String content = null;
                    
                    
                    HttpClient httpClient = new DefaultHttpClient();
                    HttpGet get = new HttpGet(url);
                    HttpResponse response = null;

                    
                    try {
						response = httpClient.execute(get);
					} catch (ClientProtocolException e1) {
						e1.printStackTrace();
					} catch (IOException e2){
						e2.printStackTrace();
					}
                    
                    try {
						content = EntityUtils.toString(response.getEntity());
					} catch (ParseException e) {
						e.printStackTrace();
					} catch (IOException e) {
						e.printStackTrace();
					}
                    
                    content = content.substring(2); //retirando o /n/n
         
                    
                    return content;
    }


    
    @Override
    protected void onPostExecute(String result) {
            
    	Usuario.setNome(result);
    	
    }
	
    }
}
