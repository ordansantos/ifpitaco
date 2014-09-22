package br.edu.ifpb;

import br.edu.ifpb.ifpitaco_mobile.R;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;

public class FiscalizarActivity extends Activity implements OnClickListener {

	private Button BtEnviar; 
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_fiscalizar);

		BtEnviar = (Button) findViewById(R.id.BtEnvFiscaliza);
		BtEnviar.setOnClickListener(this);
	}

	@Override
	public void onClick(View v) {
		startActivity(new Intent(this, FiscalizacoesActivity.class));
		
	}
}

