package com.healthcare.client;

import android.content.Context;
import android.widget.Toast;

public class Commons {
	// context gives us the environment
	public static Context context;
	// short messasges with grey background which appear in the bottom region of the app
	public static Toast toast;
	
	public Commons(Context context)
	{
		Commons.context = context;
	}
	public static void showToast(String msg, boolean isShort)
	{
		int dur;
		if(isShort)
			dur = Toast.LENGTH_SHORT;
		else
			dur = Toast.LENGTH_LONG;
		if(toast != null)
			toast.cancel();
		toast = Toast.makeText(context, msg, dur);
		toast.show();
	}
	
	public static boolean isNormal(int pulse, int bs, int bp, int temp)
	{
		if( Commons.isPulseOK(pulse) &&
				Commons.isSugarOK(bs) &&
				Commons.isPressureOK(bp) &&
				Commons.isTemperatureOK(temp))
			return true;
		return false;
	}
	
	public static boolean isPulseOK(int pulse){
		if( pulse > 60 && pulse < 100 )
			return true;
		return false;
	}
	
	public static boolean isSugarOK(int bs){
		if( bs > 70 && bs < 130 )
			return true;
		return false;
	}
	
	public static boolean isPressureOK(int bp){
		if( bp > 60 && bp < 130 )
			return true;
		return false;
	}
	
	public static boolean isTemperatureOK(int temp){
		if( temp > 95 && temp < 100)
			return true;
		return false;
	}
}
