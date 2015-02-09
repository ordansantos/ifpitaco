package br.edu.ifpb.activity;

import java.util.ArrayList;
import java.util.concurrent.ExecutionException;

import org.apache.http.HttpResponse;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONObject;

import br.edu.ifpb.asynctask.GetNPosts;
import br.edu.ifpb.ifpitaco_mobile.R;
import br.edu.ifpb.servico.HttpService;
import android.app.Activity;
import android.os.AsyncTask;
import android.os.Bundle;
import android.widget.ArrayAdapter;
import android.widget.ListView;

public class PostActivity extends Activity {

	private ListView listview;
	private ArrayList<String> myArrayList;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_post);

		listview = (ListView) findViewById(R.id.listPosts);

		GetNPosts getNPosts = new GetNPosts();

		try {
			myArrayList = getNPosts.execute().get();
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			// e.printStackTrace();
		} catch (ExecutionException e) {
			// TODO Auto-generated catch block
			// e.printStackTrace();
		}

		ArrayAdapter<String> adapter = new ArrayAdapter<String>(
				getBaseContext(), android.R.layout.simple_list_item_1,
				myArrayList);

		listview.setAdapter(adapter);

	}

}
