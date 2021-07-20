package com.healthcare.client;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Random;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.ParseException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.conn.HttpHostConnectException;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.location.Criteria;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.net.Uri;
import android.os.Bundle;
import android.os.Looper;
import android.text.format.DateFormat;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.EditText;

import com.google.gson.Gson;


public class HomeActivity extends Activity implements Runnable, LocationListener {
	
	Intent navi;
	Dialog sd;
	 private LocationManager locationManager;
	 private String provider;
	PatientObj obj;
	Thread t1;
	Gson gs = new Gson();
	HttpClient cli;
	HttpPost post;
	HttpResponse res;
	HttpEntity resent;
	String result;
	 boolean genearateRandomPHI=false;
	 int temp,press,pulse=0,sugar;
	
	
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);
        try{
        	
    	t1=new Thread(this);
    	t1.start();

        int id = this.getIntent().getExtras().getInt("uid");
      
        // getting user information from web service
      
        	 cli = new DefaultHttpClient();
             post = new HttpPost("http://"+Config.ipaddr+"/Mobile_Healthcare/getmu.php?uid=" + id);
             res = cli.execute(post);
             resent = res.getEntity();
             // conversion to string.
             result = EntityUtils.toString(resent);
             System.out.println("rrres"+result);
            // convertng JSON to Object..
             obj = gs.fromJson(result, PatientObj.class);
             obj.setId(id);
        }
        catch (HttpHostConnectException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			Commons.showToast("Can't reach server, check the Hostname", true);
		} catch (ParseException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch(Exception e){
			e.printStackTrace();
			Commons.showToast("Something wrong."+e.getMessage(), true);
		}
    }

    
    
   
    // emergency call
    public void onEmergency(View v)
    {
    	 String url = "tel:" + obj.getEmergencyNo();
    	 // intent indicates activity changes.
    	 Intent intent = new Intent(Intent.ACTION_CALL, Uri.parse(url));
    	 // making the call
    	 startActivity(intent);
    }
    
    // sending report
   public void onSendReport(View v)
    {
    	// show dialog for getting PHI data
    	
    	sd = new Dialog(this);
    	LayoutInflater li = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    	View dv = li.inflate(R.layout.dialog_report, null);
    	sd.setContentView(dv);
    	sd.setCancelable(true);
    	sd.setTitle("Send Report");
    	genearateRandomPHI=true;
    	Random random=new Random();
    	if(this.pulse==0){//If Send Report clicked for the first time
    		if(obj.getEmergencyNo().endsWith("1")){
    			pulse=101+random.nextInt(10);//emergency user
    			temp=95+random.nextInt(8);
    	    	press=50+random.nextInt(100);
    	    	sugar=60+random.nextInt(90);
    		}else{
    			pulse=60+random.nextInt(30);//normal user
    			temp=96+random.nextInt(5);
    	    	press=80+random.nextInt(39);
    	    	sugar=90+random.nextInt(20);
    		}
	    	
    	}
    	sd.show();
    
    	
    }
    
   
    public void onViewReport(View v)
    {
    	Intent repMU = new Intent(this, ReportActivity.class);
    	repMU.putExtra("pid", obj.getId());
    	startActivity(repMU);
    }
    
    
    
    public void onLogout(View v)
    {
    	
    	finish();
    }
    
    public void onCancel(View v)
    {
    	
    	genearateRandomPHI=false;
    	sd.dismiss();
    }
    // send random variables.
    public void sendReport(){
     	Random random=new Random();
     	int increaseOrDecreaseInt=random.nextInt(3);
     	boolean increase=increaseOrDecreaseInt>=1;//increase all phi values if random number is 1/2
     	if(increase){
     		this.pulse =this.pulse+random.nextInt(5) ;//60-100
     		this.sugar = this.sugar+random.nextInt(5) ;//70-140
     		this.press = this.press+random.nextInt(3) ;//60-140
     		this.temp = this.temp+random.nextInt(2) ;//97-100
     	}else{
     		this.pulse =this.pulse-random.nextInt(5) ;//60-100
         	 this.sugar = this.sugar-random.nextInt(5) ;//70-140
         	this.press = this.press-random.nextInt(3) ;//60-140
         	this.temp = this.temp-random.nextInt(2) ;//97-100
     	}
      	System.out.println("pulse"+pulse);
      	runOnUiThread(new Runnable() {     
            @Override
            public void run() {
            	( (EditText)sd.findViewById(R.id.txtPulse)).setText(""+pulse);
              	( (EditText)sd.findViewById(R.id.txtSugar)).setText(""+sugar);
              	( (EditText)sd.findViewById(R.id.txtPressure)).setText(""+press);
              	( (EditText)sd.findViewById(R.id.txtTemperature)).setText(""+temp);
              	
              	if(!Commons.isPressureOK(press))
              		( (EditText)sd.findViewById(R.id.txtPressure)).setBackgroundColor(Color.RED);
              	if(!Commons.isPulseOK(pulse))
              		( (EditText)sd.findViewById(R.id.txtPulse)).setBackgroundColor(Color.RED);
              	if(!Commons.isSugarOK(sugar))
              		( (EditText)sd.findViewById(R.id.txtSugar)).setBackgroundColor(Color.RED);
              	if(!Commons.isTemperatureOK(temp))
              		( (EditText)sd.findViewById(R.id.txtTemperature)).setBackgroundColor(Color.RED);
              	
              	
            }
        });
  	
      	
      	// sending report to the service...
      	PhiObj phiobj = new PhiObj();
      //	Location l=getLocation();
      //	phiobj.setPName(obj.getPName());
      	phiobj.setPid(obj.getId());
      	phiobj.setRepTime(DateFormat.format("dd/MMM/yyyy - hh.mm a", new Date()).toString());
      	phiobj.setPulse(pulse);
      	phiobj.setSugar(sugar);
      	phiobj.setPressure(press);
      	phiobj.setTemperature(temp);
      	//AndroidKeyStore keystore=new AndroidKeyStore(this);
      //	keystore.addPref("u", "339da84d44aa94398577612dcbf2d8215965bb956248d96fb8840bfd56ff3d3cbab43fb9ac2f19b2ff7fbd21bcd816ae7943a0e6846fcdcf2ac9ead58238893c695a90ca35be932b037d37a6b0a6f9af776a9167870d4a11b4c011c24d001b342b109c5e0184e35133931d8ebbc4851887cda0662a0dce22904084013ea8208b");
      	//System.out.println("Preference->"+keystore.getPref("u"));
      	
      	GPSTracker gps=new GPSTracker(this);
      	System.out.println("Loccc"+gps.getLocation());
      	if(gps.getLatitude()!=0.0){
      		phiobj.setLat(""+gps.getLatitude());
      		phiobj.setLng(""+gps.getLongitude());
      	}else{
//      		phiobj.setLat(Config.defaultLatitude);9.66836101723838
//      		phiobj.setLng(Config.defaultLongitude);, 76.62123625071573
      	}
      	
      	if(Commons.isNormal(pulse, sugar, press, temp))
      		phiobj.setStatus("Normal");
      	else
      		phiobj.setStatus("Emergency");
      	
      	
      	String json = gs.toJson(phiobj);
//      	
      	System.out.println("JSon"+json);
      	// sending report to the web service
      	try{
      		post = new HttpPost("http://"+Config.ipaddr+"/Mobile_Healthcare/recPHI.php");
      		List<NameValuePair> rep = new ArrayList<NameValuePair>(3);
      		rep.add(new BasicNameValuePair("phirep", json));
//      		
      		post.setEntity(new UrlEncodedFormEntity(rep));
          	res = cli.execute(post);
          	resent = res.getEntity();
          	result = EntityUtils.toString(resent);
          	System.out.println("Result======="+result);
          	if(result.equals("true"))
          		Commons.showToast("Report sent to TA", true);
          	else
          		Commons.showToast("Can't sent report to TA\n" + result, true);
      	}
      	catch(Exception e){
      		Commons.showToast("Something wrong..Please check your connection..", true);
      		e.printStackTrace();
      	}
      
  	

    }
    
    public Location getLocation(){
    	
  	    locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
  	    
  	    // Define the criteria how to select the locatioin provider -> use
  	    // default
  	    Criteria criteria = new Criteria();
  	    provider = locationManager.getBestProvider(criteria, false);
  	   // System.out.println("Provider is"+provider);
  	    Location location = locationManager.getLastKnownLocation(provider);
  	   
  	    // Initialize the location fields
  	    if (location != null) {
  	      System.out.println("Provider " + provider + " has been selected.");
  	      System.out.println("Location is "+location);
  	     
  	      onLocationChanged(location);
  	      int lat = (int) (location.getLatitude());
  		    int lng = (int) (location.getLongitude());
  		    System.out.println("long"+lng+"lat"+lat);
  	    } else {
  	     System.out.println("location not available");
  	    }
  	    return location;
    }
    public void onReportSend(View v)
    {
    	
    	
    	sendReport();
    	
    
    }
    
    @Override
    public void onActivityResult(int reqCode, int resCode, Intent data)
    {
    	if(resCode == Activity.RESULT_OK)
    	{
    		Bundle bundle = data.getExtras();
    		obj = (PatientObj) bundle.getSerializable("Extra_Obj");
    	}
    }
    
    @Override
    public void onBackPressed()
    {
    	
    }
    // to continously run the threads.
    public void run(){
        Looper.prepare();
   	 while(true){
      	if(genearateRandomPHI){
      		
//      		System.out.println("Get Location");
//      		GPSTracker gps=new GPSTracker(this);
//      		System.out.println(gps.getLocation());
//      		System.out.println(gps.getLocation());
      		
      		sendReport();
      		//System.out.println("Sending report");
      		try {
					Thread.sleep(10000);
				} catch (InterruptedException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
      	}
      	try {
				Thread.sleep(50);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
      }
    	 
        
    }

	@Override
	public void onLocationChanged(Location arg0) {
		// TODO Auto-generated method stub
		System.out.println("Location changed------"+arg0);
	}

	@Override
	public void onProviderDisabled(String arg0) {
		// TODO Auto-generated method stub
		
	}

	@Override
	public void onProviderEnabled(String arg0) {
		// TODO Auto-generated method stub
		
	}

	@Override
	public void onStatusChanged(String arg0, int arg1, Bundle arg2) {
		// TODO Auto-generated method stub
		
	}
	
}
