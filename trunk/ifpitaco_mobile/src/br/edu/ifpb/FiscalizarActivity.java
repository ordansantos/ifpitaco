package br.edu.ifpb;

import java.util.ArrayList;
import java.util.Arrays;

import br.edu.ifpb.ifpitaco_mobile.R;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;

public class FiscalizarActivity extends Activity implements OnClickListener {

	private Spinner SpEntidades;
	private ArrayAdapter<String> listAdapter;
	private Button BtEnviar; 
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_fiscalizar);

		BtEnviar = (Button) findViewById(R.id.BtEnvFiscaliza);
		BtEnviar.setOnClickListener(this);
		
		String entidades[] = new String[]{"Infraestrutura", "Ensino", "Gestão"};
		
		ArrayList<String> entidadesList = new ArrayList<String>();
		entidadesList.addAll(Arrays.asList(entidades));
		
		listAdapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_dropdown_item, entidadesList); 
		
		SpEntidades.setAdapter(listAdapter);
	}

	@Override
	public void onClick(View v) {
		startActivity(new Intent(this, FiscalizacoesActivity.class));
		
	}
}

