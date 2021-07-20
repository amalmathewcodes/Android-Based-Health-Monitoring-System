package com.healthcare.client;

import java.io.IOException;
import java.util.ArrayList;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.ParseException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.conn.HttpHostConnectException;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;

import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonParser;
import com.healthcare.client.R;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.AdapterView.OnItemClickListener;

public class ReportActivity extends Activity{
	//
	Gson gs = new Gson();
	HttpClient cli;
	HttpPost post;
	HttpResponse res;
	HttpEntity resent;
	String result;
	
	ListView lv;
	ArrayList<PhiObj> reports = new ArrayList<PhiObj>();
	//Adapter that exposes data from a Cursor to a ListView widget.
	MyCursorAdapter myAda;
	
	AlertDialog dialogResponse;
	AlertDialog.Builder builderResponse;
	
	TextView itemrid;
	TextView itemrtime;
	TextView itempulse;
	TextView itemsugar;
	TextView itempressure;
	TextView itemtemp;
	TextView itemstate;
	TextView itemrespond;
	
	
	
	int pid;
	
	@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_report);


        pid = this.getIntent().getExtras().getInt("pid"); 
        
        
        try{
        	cli = new DefaultHttpClient();
            post = new HttpPost("http://" + Config.ipaddr+ "/Mobile_Healthcare/mu_report.php?pid=" + pid);
            res = cli.execute(post);
            resent = res.getEntity();
            result = EntityUtils.toString(resent);
            System.out.println("Result:"+result);
            JsonParser jpar = new JsonParser();
            JsonArray jarr = jpar.parse(result).getAsJsonArray();
            
            // mapping report array to PhiObj arraylist
            for( int i = 0; i < jarr.size(); i++ )
            {
            	PhiObj singlePhi = gs.fromJson(jarr.get(i).toString(), PhiObj.class);
            	reports.add(singlePhi);
            }
        }
        catch (HttpHostConnectException e) {
			
			e.printStackTrace();
			Commons.showToast("Can't reach server, check the Hostname", true);
		} catch (ParseException e) {
			
			e.printStackTrace();
		} catch (IOException e) {
			
			e.printStackTrace();
		}
        
        
        lv = (ListView) findViewById(R.id.repList);
        myAda = new MyCursorAdapter();
        lv.setAdapter(myAda);
        lv.setOnItemClickListener(new OnItemClickListener(){

			@Override
			public void onItemClick(AdapterView<?> adapter, View view, int pos,
					long id) {
				
				if(pos == 0)
				{
					
					AlertDialog ad;
					AlertDialog.Builder adbuild = new AlertDialog.Builder(ReportActivity.this);
					adbuild.setTitle("Header Summary");
					adbuild.setMessage("Id - Id" +
							"\nTime - Report Time" +
							"\nPulse - Pulse Rate" +
							"\nBS - Blood Sugar Level" +
							"\nBP - Blood Pressure Level" +
							"\nTemp - Body Temperature" +
							"\nState - Status(Normal/Emergency)");
				
					adbuild.setPositiveButton("Ok", null);
					ad = adbuild.create();
					ad.show();
				}
				else
				{
					final PhiObj obj = reports.get(pos - 1);
					if(obj.getDiagnosis() == null||obj.getDiagnosis().equals(""))
						// toast shows the small message part
						Commons.showToast("Not yet Responded", true);
					else
					{
						
						builderResponse = new AlertDialog.Builder(ReportActivity.this);
						builderResponse.setTitle("Diagnosis");
						builderResponse.setMessage(obj.getDiagnosis());
						builderResponse.setPositiveButton("Ok", null);
						dialogResponse = builderResponse.create();
						dialogResponse.show();
					}
				}
			}
        });
	}
	
	public class MyCursorAdapter extends BaseAdapter
    {

		@Override
		public int getCount() {
			
			return reports.size() + 1;
			
		}

		@Override
		public Object getItem(int arg0) {
			
			return null;
		}

		@Override
		public long getItemId(int arg0) {
			
			return 0;
		}

		
		@Override
		public View getView(int pos, View convertView, ViewGroup parent) {


			View v = null;
			if(convertView == null)
			{
				LayoutInflater li = getLayoutInflater();
				v = li.inflate(R.layout.mu_report_list_item, null);
			}
			else
				v = convertView;
			
			itemrid = (TextView) v.findViewById(R.id.itemRid); 
			itemrtime = (TextView) v.findViewById(R.id.itemTime);
			itempulse = (TextView) v.findViewById(R.id.itemPulse);
			itemsugar = (TextView) v.findViewById(R.id.itemSugar);
			itempressure = (TextView) v.findViewById(R.id.itemPressure);
			itemtemp = (TextView) v.findViewById(R.id.itemTemperature);
			itemstate = (TextView) v.findViewById(R.id.itemStatus);
			itemrespond = (TextView) v.findViewById(R.id.itemResponded);
			
			if(pos == 0)
			{
				itemrid.setText("Rep Id");
				itemrtime.setText("Rep Time");
				itempulse.setText("Pulse");
				itemsugar.setText("BS");
				itempressure.setText("BP");
				itemtemp.setText("Temp");
				itemstate.setText("State");
				itemrespond.setText("Response");
				
				itemrespond.setTextColor(Color.BLACK);
				itempulse.setTextColor(Color.BLACK);
				itemsugar.setTextColor(Color.BLACK);
				itempressure.setTextColor(Color.BLACK);
				itemtemp.setTextColor(Color.BLACK);
				itemstate.setTextColor(Color.BLACK);
			}
			else
			{	
				PhiObj obj = reports.get(pos - 1);
				itemrid.setText(Integer.toString(obj.getId()));
				itemrtime.setText(obj.getRepTime());
				
				
				itempulse.setText(Integer.toString(obj.getPulse()));
				if(Commons.isPulseOK(obj.getPulse()))
					itempulse.setTextColor(Color.BLACK);
				else
					itempulse.setTextColor(Color.RED);
				
				
				itemsugar.setText(Long.toString(obj.getSugar()));
				if(Commons.isSugarOK(obj.getSugar()))
					itemsugar.setTextColor(Color.BLACK);
				else
					itemsugar.setTextColor(Color.RED);
				
				
				itempressure.setText(Long.toString(obj.getPressure()));
				if(Commons.isPressureOK(obj.getPressure()))
					itempressure.setTextColor(Color.BLACK);
				else
					itempressure.setTextColor(Color.RED);
				
				
				itemtemp.setText(Double.toString(obj.getTemperature()));
				if(Commons.isTemperatureOK(obj.getTemperature()))
					itemtemp.setTextColor(Color.BLACK);
				else
					itemtemp.setTextColor(Color.RED);
				
				
				itemstate.setText(obj.getStatus());
				if(obj.getStatus().equals("Normal"))
					itemstate.setTextColor(Color.BLACK);
				else
					itemstate.setTextColor(Color.RED);
								
				if(obj.getDiagnosis() != null&&!obj.getDiagnosis().equals("")) 
				{
					itemrespond.setText("Responded");
					itemrespond.setTextColor(Color.BLUE);
				}	
				else
				{
					itemrespond.setText("Waiting");
					itemrespond.setTextColor(Color.BLACK);
				}
			}
			return v;
    	}
    }
}
