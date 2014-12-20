package br.edu.ifpb;

import br.edu.ifpb.activity.LoginActivity;
import br.edu.ifpb.ifpitaco_mobile.R;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;

public class SplashScreen extends Activity implements Runnable {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_splash_screen);
		
		Handler handler = new Handler();
		handler.postDelayed(this, 3000);
	}

	@Override
	public void run() {
		startActivity(new Intent(this, LoginActivity.class));
		finish();
	}
}
