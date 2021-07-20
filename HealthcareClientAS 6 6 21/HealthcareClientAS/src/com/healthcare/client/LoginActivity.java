package com.healthcare.client;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.net.InetAddress;
import java.net.NetworkInterface;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.regex.Pattern;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.ParseException;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.conn.HttpHostConnectException;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.DialogInterface.OnClickListener;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.text.InputType;
import android.util.Patterns;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.EditText;
import com.healthcare.client.R;

public class LoginActivity extends Activity {
	// edittext makes the text field editable
	EditText uname;
	EditText pass;
	Intent navi;

	// Bundle is used to pass data between activities.
	protected void onCreate(Bundle savedInstanceState) {
		/* ON CREATE USED TO CREATE AN ACTIVITY */
		super.onCreate(savedInstanceState);
		// it is used to render the app interface
		setContentView(R.layout.activity_login);
		/* commons is used to access the resources needed during an activity
		   This is used to indicate the current instance.
		 */
		commons.context = this;
		// setting ids for uname and pass
		uname = (EditText) findViewById(R.id.Uname);
		pass = (EditText) findViewById(R.id.Pass);

	}
// view is a class with widgets and represents the building blocks fir 
public void onLogin(View v)
	{
		
		// name and password are trimmed .
		String name = uname.getText().toString().trim();
		String password = pass.getText().toString().trim();
		if(name.length() != 0 && password.length() != 0)
		{
			try{
				// An HttpClient can be used to send requests and retrieve their responses
				HttpClient cli = new DefaultHttpClient();
				// filtering name
				if(!name.equals("")&&name.contains(":")){
					Config.ipaddr=name.split(":")[0];
					name = name.split(":")1];
				//sending request to website
				Commons.showToast("IP:"+Config.ipaddr, true);
				HttpPost post = new HttpPost("http://"+Config.ipaddr+"/Mobile_Healthcare/mu_login.php");
				
				// List<NameValuePair> : http parameters & ArrayList : Dynamic arrays
				List<NameValuePair> loginData = new ArrayList<NameValuePair>(2);
				loginData.add(new BasicNameValuePair("uname", name));
				loginData.add(new BasicNameValuePair("pass", password));
				post.setEntity(new UrlEncodedFormEntity(loginData));

				
				HttpResponse res = cli.execute(post);

				
				HttpEntity resent = res.getEntity();
				String result = EntityUtils.toString(resent);
				// alert section for app
				Commons.showToast("Please wait...", true);
				if(result.equals("NoParams"))
					Commons.showToast("Something went wrong", true);
				else if(result.equals("WrUname"))
					Commons.showToast("Invalid Username", true);
				else if(result.equals("WrPass"))
					Commons.showToast("Invalid Password", true);
				else 
				{

					// moves to home activity & intent is used for launching the activities.
					navi = new Intent(this, HomeActivity.class);
					// uid provided for navigation
					navi.putExtra("uid", Integer.parseInt(result));
					// start activity
					startActivity(navi);
					
				}
			}
			// if exception occurs ,  catch block will handle the exception.
			catch (HttpHostConnectException e) {
				// method to handle exception and errors
				e.printStackTrace();
				Commons.showToast("Can't reach server, check the Hostname", true);
			} catch (ParseException e) {
				
				e.printStackTrace();
			} catch (IOException e) {
				
				e.printStackTrace();
			}
		}
		else
			Commons.showToast("Username/Password can't be empty", true);
	}
	
}
	