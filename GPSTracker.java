package com.healthcare.client;

import android.app.AlertDialog;
import android.app.Service;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.os.IBinder;
import android.provider.Settings;
import android.util.Log;
/** Activity represents single screen with user interface
 *  LocationListener Used for receiving notifications when the device location has changed
 */
public class GPSTracker extends Service implements LocationListener {

    private final Context mContext;


    boolean isGPSEnabled = false;

    
    boolean isNetworkEnabled = false;

    
    boolean canGetLocation = false;

    Location location; 
    double latitude; 
    double longitude; 

    
    private static final long MIN_DISTANCE_CHANGE_FOR_UPDATES = 10;

    
    private static final long MIN_TIME_BW_UPDATES = 1000 * 60 * 1; 

    
    protected LocationManager locationManager;


    public GPSTracker(Context context) {
        this.mContext = context;
        getLocation();
    }

    public Location getLocation() {
        try {
            locationManager = (LocationManager) mContext
                    .getSystemService(LOCATION_SERVICE);

            
            isGPSEnabled = locationManager
                    .isProviderEnabled(LocationManager.GPS_PROVIDER);

           
            isNetworkEnabled = locationManager
                    .isProviderEnabled(LocationManager.NETWORK_PROVIDER);
            if (!isGPSEnabled){
            	showSettingsAlert();
            }
            if (!isGPSEnabled && !isNetworkEnabled) {
                // No network provider is enabled
            	Commons.showToast("Please enable GPS/network",true);
            	
            } else {
                this.canGetLocation = true;
                if (isNetworkEnabled) {
                    locationManager.requestLocationUpdates(
                            LocationManager.NETWORK_PROVIDER,
                            MIN_TIME_BW_UPDATES,
                            MIN_DISTANCE_CHANGE_FOR_UPDATES, this);
                    Log.d("Network", "Network");
                    if (locationManager != null) {
                        location = locationManager
                                .getLastKnownLocation(LocationManager.NETWORK_PROVIDER);
                        if (location != null) {
                            latitude = location.getLatitude();
                            longitude = location.getLongitude();
                            System.out.println("lat"+latitude+"long"+longitude);
                        }
                    }
                }
                
                if (isGPSEnabled) {
                    if (location == null) {
                        locationManager.requestLocationUpdates(
                                LocationManager.GPS_PROVIDER,
                                MIN_TIME_BW_UPDATES,
                                MIN_DISTANCE_CHANGE_FOR_UPDATES, this);
                        Log.d("GPS Enabled", "GPS Enabled");
                        if (locationManager != null) {
                            location = locationManager
                                    .getLastKnownLocation(LocationManager.GPS_PROVIDER);
                            if (location != null) {
                                latitude = location.getLatitude();
                                longitude = location.getLongitude();
                                System.out.println("lat"+latitude+"long"+longitude);
                            }
                        }
                    }
                }
            }
        }
        catch (Exception e) {

            e.printStackTrace();
        }

        return location;
    }


  
    public void stopUsingGPS(){
        if (locationManager != null) {
            locationManager.removeUpdates(GPSTracker.this);
        }
    }


   
    public double getLatitude(){
        if(location != null){
            latitude = location.getLatitude();
        }

        
        return latitude;
    }


    
    public double getLongitude(){
        if(location != null){
            longitude = location.getLongitude();
        }

       
        return longitude;
    }

    /**
     * Function to check GPS/Wi-Fi enabled
     * @return boolean
     * */
    public boolean canGetLocation() {
        return this.canGetLocation;
    }


    /**
     * Function to show settings alert dialog.
     * On pressing the Settings button it will launch Settings Options.
     * */
    public void showSettingsAlert(){
        AlertDialog.Builder alertDialog = new AlertDialog.Builder(mContext);

        // Setting Dialog Title
        alertDialog.setTitle("GPS settings");

        // Setting Dialog Message
        alertDialog.setMessage("GPS is not enabled. Do you want to go to settings menu?");

        // On pressing the Settings button.
        alertDialog.setPositiveButton("Settings", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog,int which) {
                Intent intent = new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS);
                mContext.startActivity(intent);
            }
        });

        // On pressing the cancel button
        alertDialog.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int which) {
            dialog.cancel();
            }
        });

        // Showing Alert Message
        alertDialog.show();
    }


  

    @Override
    public void onProviderDisabled(String provider) {
   }


    @Override
   public void onProviderEnabled(String provider) {
   }


    


  

	@Override
	public void onLocationChanged(Location arg0) {
	
		System.out.println("loccccc"+arg0);
	}

	@Override
	public void onStatusChanged(String arg0, int arg1, Bundle arg2) {
		
		
	}

	@Override
	public IBinder onBind(Intent arg0) {
		
		return null;
	}
}