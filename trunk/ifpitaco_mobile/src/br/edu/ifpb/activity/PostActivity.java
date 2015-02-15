package br.edu.ifpb.activity;

import java.util.ArrayList;
import java.util.concurrent.ExecutionException;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ListView;
import br.edu.ifpb.asynctask.GetFotoPerfilAsyncTask;
import br.edu.ifpb.asynctask.GetNPostsAsyncTask;
import br.edu.ifpb.ifpitaco_mobile.R;

public class PostActivity extends Activity implements OnClickListener {

	private ListView listview;
	private ArrayList<String> myArrayList;
	private Button btEnquetes;
	private ImageView btPerfil;
	private Bitmap img;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_post);

		listview = (ListView) findViewById(R.id.listPosts);
		btEnquetes = (Button) findViewById(R.id.btEnquetesPost);
		btPerfil = (ImageView) findViewById(R.id.btImagemPerfilPost);

		GetNPostsAsyncTask getNPosts = new GetNPostsAsyncTask();
		GetFotoPerfilAsyncTask getFtPerfAsyncTask = new GetFotoPerfilAsyncTask();

		try {
			myArrayList = getNPosts.execute().get();
			img = getFtPerfAsyncTask.execute().get();
		} catch (InterruptedException e) {
			e.printStackTrace();
		} catch (ExecutionException e) {
			e.printStackTrace();
		}

		btPerfil.setImageBitmap(img);
		
		ArrayAdapter<String> adapter = new ArrayAdapter<String>(
				getBaseContext(), android.R.layout.simple_list_item_1,
				myArrayList);

		listview.setAdapter(adapter);
		btEnquetes.setOnClickListener(this);
		btPerfil.setOnClickListener(this);
	}

	@Override
	public void onClick(View v) {
		Intent i = null;
		switch (v.getId()) {
		case R.id.btEnquetesPost:
			i = new Intent(this, EnqueteActivity.class);
			
			break;
		case R.id.btImagemPerfilPost:
			i = new Intent(this, PerfilActivity.class);
			break;
		}
		
		startActivity(i);
	}

}
