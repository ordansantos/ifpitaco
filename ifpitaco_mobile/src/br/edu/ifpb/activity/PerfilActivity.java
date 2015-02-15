package br.edu.ifpb.activity;

import java.util.HashMap;
import java.util.concurrent.ExecutionException;

import android.app.Activity;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.TextView;
import br.edu.ifpb.asynctask.GetFotoPerfilAsyncTask;
import br.edu.ifpb.asynctask.PerfilAsyncTask;
import br.edu.ifpb.ifpitaco_mobile.R;

public class PerfilActivity extends Activity {
	
	private TextView nome;
	private TextView tipo;
	private TextView curso;
	private TextView ano;
	private TextView grauAcademico;
	private ImageView fotoPerfil;
	private HashMap<String,String> hm;
	private Bitmap img;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_perfil);
		
		nome = (TextView) findViewById(R.id.tvNomePerfil);
		tipo = (TextView) findViewById(R.id.tvTipoPerfil);
		curso = (TextView) findViewById(R.id.tvCursoPerfil);
		ano = (TextView) findViewById(R.id.tvAnoPerfil);
		grauAcademico = (TextView) findViewById(R.id.tvGAcadPerfil);
		fotoPerfil = (ImageView) findViewById(R.id.ivPerfil);
		
		PerfilAsyncTask perfilasynctask = new PerfilAsyncTask();
		GetFotoPerfilAsyncTask getFtPerfAsyncTask = new GetFotoPerfilAsyncTask();
		
		try {
			hm = perfilasynctask.execute().get();
			img = getFtPerfAsyncTask.execute().get();
		} catch (InterruptedException e) {
			e.printStackTrace();
		} catch (ExecutionException e) {
			e.printStackTrace();
		}
		
		fotoPerfil.setImageBitmap(img);
		inserirDados();		
		
	}
	
	public void inserirDados() {
		nome.setText(hm.get("nome").length() > 0 ? hm.get("nome") : "Não informado.");
		tipo.setText(hm.get("tipo").length() > 0 ? hm.get("tipo") : "Não informado.");
		curso.setText(hm.get("curso").length() > 0 ? hm.get("curso") : "Não informado.");
		ano.setText(hm.get("ano").length() > 0 ? hm.get("ano") : "Não informado.");
		grauAcademico.setText(hm.get("grauAcademico").length() > 0 ? hm.get("grauAcademico") :"Não informado.");
		
	}

}
