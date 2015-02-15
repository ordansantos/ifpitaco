package br.edu.ifpb.asynctask;

import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;

import org.apache.http.HttpResponse;
import org.apache.http.util.EntityUtils;

import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.AsyncTask;
import br.edu.ifpb.entidades.Usuario;
import br.edu.ifpb.servico.HttpService;

public class GetFotoPerfilAsyncTask extends AsyncTask<Void, Void, Bitmap> {

		@Override
		protected Bitmap doInBackground(Void... params) {
			String content = null;
			Bitmap img = null;
			
			try {

				HttpResponse response = HttpService.sendGETRequest("getFotoPerfilById/"
						+ Usuario.getId());

				content = EntityUtils.toString(response.getEntity());
				content = content.substring(2); // retirando o /n/n
				content = content.replace("WebService/", "");
				
				
				URL url = new URL(HttpService.getUrl()+content);
				HttpURLConnection conexao = (HttpURLConnection) url.openConnection();
				InputStream input = conexao.getInputStream();

				img = BitmapFactory.decodeStream(input);
			} catch (IOException e) {
				e.printStackTrace();
			}
			return img;
		}


		
	}