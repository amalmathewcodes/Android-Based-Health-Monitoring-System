package com.healthcare.client;

import java.io.Serializable;

public class PhiObj implements Serializable {

	
	private static final long serialVersionUID = -8385784430620938076L;
	private int Id;
	private int Pid;
	
	private String RepTime;
	private int PulseRate;
	private int BSugar;
	private int BPressure;
	private int Temperature;
	private String Status;
	private String Diagnosis;
	
	private String lng;
	private String lat;
		
	
	public PhiObj(int id, int pid, String time, int pulse, int bs, int bp, int temp, String status, String diag, String diagtime)
	{
		this.Id = id;
		this.Pid = pid;
		
		this.RepTime = time;
		this.PulseRate = pulse;
		this.BSugar = bs;
		this.BPressure = bp;
		this.Temperature = temp;
		this.Status = status;
		this.Diagnosis = diag;
		
	}
	
	public PhiObj(){}
	
	public void setId(int id)
	{
		this.Id = id;
	}
	
	public int getId()
	{
		return this.Id;
	}
	
	public void setPid(int pid)
	{
		this.Pid = pid;
	}
	
	public int getPid()
	{
		return this.Pid;
	}
	public void setLat(String lat)
	{
		this.lat = lat;
	}
	public void setLng(String lng)
	{
		this.lng= lng;
	}
	public String getLng()
	{
		return this.lng;
	}
	public String getLattitude()
	{
		return this.lat;
	}
	
	//public void setPName(String pname)
	//{
	//	this.PName = pname;
	//}
	
	//public String getPName()
	//{
	//	return this.PName;
	//}
	
	public void setRepTime(String time)
	{
		this.RepTime = time;
	}
	
	public String getRepTime()
	{
		return this.RepTime;
	}
	
	public void setPulse(int pulse)
	{
		this.PulseRate = pulse;
	}
	
	public int getPulse()
	{
		return this.PulseRate;
	}
	
	public void setSugar(int sugar)
	{
		this.BSugar = sugar;
	}
	
	public int getSugar()
	{
		return this.BSugar;
	}
	
	public void setPressure(int press)
	{
		this.BPressure = press;
	}
	
	public int getPressure()
	{
		return this.BPressure;
	}
	
	public void setTemperature(int temp)
	{
		this.Temperature = temp;
	}
	
	public int getTemperature()
	{
		return this.Temperature;
	}
	
	public void setStatus(String state)
	{
		this.Status = state;
	}
	
	public String getStatus()
	{
		return this.Status;
	}
	
	public void setDiagnosis(String diag){
		this.Diagnosis = diag;
	}
	
	public String getDiagnosis(){
		return this.Diagnosis;
	}
	
	

}
