package br.edu.ifpb.activity;


import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.ExecutionException;

import org.json.JSONException;
import org.json.JSONObject;

import br.edu.ifpb.asynctask.RamosAsyncTask;
import br.edu.ifpb.entidades.Ramo;
import br.edu.ifpb.entidades.Usuario;
import br.edu.ifpb.ifpitaco_mobile.R;
import br.edu.ifpb.servico.HttpService;
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

	private Spinner spEntidades;
	private ArrayAdapter<String> listAdapter;
	private Button btEnviar;
	private EditText etPropor;
	private List<Ramo> ramos;
	private Ramo ramo;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_propor);
		spEntidades = (Spinner) findViewById(R.id.SpEntidadesPropor);
		btEnviar = (Button) findViewById(R.id.BtEnvProposta);
		etPropor = (EditText) findViewById(R.id.ETPropor1);

		RamosAsyncTask ramosasynctask = new RamosAsyncTask();
		ramos = null;
		ArrayList<String> entidadesList = new ArrayList<String>();

		try {
			ramos = ramosasynctask.execute().get();
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (ExecutionException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		for (Ramo r : ramos) {
			entidadesList.add(r.getRamoNome());
		}

		listAdapter = new ArrayAdapter<String>(this,
				android.R.layout.simple_spinner_dropdown_item, entidadesList);

		spEntidades.setAdapter(listAdapter);

		btEnviar.setOnClickListener(this);

	}

	@Override
	public void onClick(View v) {
		String ramoNome = (String) spEntidades.getSelectedItem();

		for (Ramo r : ramos) {
			if (r.getRamoNome().equals(ramoNome)) {
				ramo = r;
			}
		}

		if (etPropor.getText().length() == 0) {
			Toast.makeText(getBaseContext(), "Preencha os campos!",
					Toast.LENGTH_SHORT).show();
		} else
			new PostProposta().execute();

		Intent i = new Intent(this, PropostasActivity.class);
		startActivity(i);
	}

	// Post com AsyncTask
	private class PostProposta extends AsyncTask<Void, Void, Void> {

		@Override
		protected Void doInBackground(Void... params) {

			JSONObject object = new JSONObject();

			try {
				object.put("comentario", etPropor.getText());
				object.put("usuario_id", Usuario.getId());
				object.put("ramo_id", ramo.getId());
				HttpService.sendJsonPostRequest("postProposta", object);
			} catch (JSONException e) {
				e.printStackTrace();
			}


			return null;
		}

		@Override
		protected void onPreExecute() {
		}

		@Override
		protected void onProgressUpdate(Void... values) {
		}

		@SuppressLint("ShowToast")
		@Override
		protected void onPostExecute(Void result) {

			EditText comentario = (EditText) findViewById(R.id.ETPropor1);
			comentario.setText("");

			Toast.makeText(getBaseContext(), "Enviado!", Toast.LENGTH_SHORT)
					.show();
		}
	}

}
