package br.edu.ifpb.activity;


import java.util.ArrayList;
import java.util.Arrays;
import br.edu.ifpb.ifpitaco_mobile.R;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.ListView;

public class FuncoesListActivity extends Activity {
	
	private ListView myListView;
	private ArrayAdapter<String> listAdapter;
	private Intent intent;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		
		super.onCreate(savedInstanceState);
		
		setContentView(R.layout.activity_funcoes_list);
		
		myListView = (ListView) findViewById(R.id.myListView);
		
		String funcoes[] = new String[]{"Propor", "Fiscalizar", "Avaliar"};
		
		ArrayList<String> funcaoList = new ArrayList<String>();
		
		funcaoList.addAll(Arrays.asList(funcoes));
		
		listAdapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, funcaoList);
		
		myListView.setAdapter(listAdapter);
		
		OnItemClickListener listener = new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> parent, View view,
					int position, long id) {
				
					startOption(position);
			}
		};
		
		myListView.setOnItemClickListener(listener);
	}
	
	public void startOption (int position){
		
		if (position == 0)
			intent = new Intent (this, ProporActivity.class);
		
		if (position == 1)
			intent = new Intent (this, FiscalizarActivity.class);
		
		if (position == 2)
			intent = new Intent (this, AvaliarActivity.class);
		
		startActivity(intent);
	}
}
