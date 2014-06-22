package br.edu.ifpb;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;

public class MainActivity extends Activity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		Intent i = new Intent (this, FuncoesListActivity.class);
		
		startActivity(i);
		
		
	}
}
