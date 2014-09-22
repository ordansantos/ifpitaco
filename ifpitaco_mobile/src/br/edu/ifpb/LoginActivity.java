package br.edu.ifpb;

import br.edu.ifpb.ifpitaco_mobile.R;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;

public class LoginActivity extends Activity implements OnClickListener{
	
	private EditText etNome;
	private EditText etSenha;
	private Button btEntrar;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);
		
		etNome = (EditText) findViewById(R.id.ETNomeUsuario);
		etSenha = (EditText) findViewById(R.id.ETSenhaUsuario);
		btEntrar = (Button) findViewById(R.id.BtEntrar);
		
		btEntrar.setOnClickListener(this);
		
		
	}

	@Override
	public void onClick(View v) {
		Intent i = new Intent (this, FuncoesListActivity.class);
		startActivity(i);
		
	}

}
