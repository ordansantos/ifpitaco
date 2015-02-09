package br.edu.ifpb.activity;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
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
import br.edu.ifpb.entidades.Usuario;
import br.edu.ifpb.ifpitaco_mobile.R;
import br.edu.ifpb.servico.HttpService;
import br.edu.ifpb.servico.HttpUtil;

public class LoginActivity extends Activity implements OnClickListener {

	private EditText etEmail;
	private EditText etSenha;
	private TextView btEntrar;
	private TextView tvCadastra;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);

		etEmail = (EditText) findViewById(R.id.ETEmailUsuario);
		etSenha = (EditText) findViewById(R.id.ETSenhaUsuario);
		btEntrar = (TextView) findViewById(R.id.BtEntrar);
		tvCadastra = (TextView) findViewById(R.id.cadastroTextView);

		btEntrar.setOnClickListener(this);
		tvCadastra.setOnClickListener(this);

	}

	@Override
	public void onClick(View v) {
		switch (v.getId()) {
		case R.id.BtEntrar:
			new PostLogin().execute();
			break;
		case R.id.cadastroTextView:
			Intent i = new Intent(getBaseContext(), CadastrarActivity.class);
			startActivity(i);
		}
	}

	// Post com AsyncTask
	private class PostLogin extends AsyncTask<Void, Void, HttpResponse> {

		@Override
		protected HttpResponse doInBackground(Void... params) {

			HttpResponse response = null;

			try {
				/*JSONObject object = new JSONObject();			
				object.put("email", etEmail.getText());
				object.put("senha", etSenha.getText());
				response = HttpService.sendJsonPostRequest("postLogin", object);*/
				
				String email = String.valueOf(etEmail.getText());
				List<NameValuePair> nameValuePair = new ArrayList<NameValuePair>();
				nameValuePair.add(new BasicNameValuePair("email", String.valueOf( etEmail.getText() ) ) );
				nameValuePair.add(new BasicNameValuePair("senha", String.valueOf( etSenha.getText() ) ) );
				response = HttpService.sendParamPostRequest("postLogin", nameValuePair);
				
			} catch (Exception e) {
				e.printStackTrace();
			}
			
			return response;
		}

		@SuppressLint("ShowToast")
		@Override
		protected void onPostExecute(HttpResponse response) {
			String aux = HttpUtil.entityToString(response);
			int id = Integer.valueOf(aux);

			if (id == 0) {
				Toast.makeText(getBaseContext(), "Senha ou e-mail incorretos",
						Toast.LENGTH_SHORT).show();
			} else {
				Usuario u = new Usuario();
				u.setId(id);
				u.setNomeUsuario();
				Toast.makeText(getBaseContext(),
						"Olá! Bem vindo ao IFPitaco!",
						Toast.LENGTH_SHORT).show();
				Intent i = new Intent(getBaseContext(),
						PostActivity.class);
				startActivity(i);
			}

		}

	}

}
