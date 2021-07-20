package com.healthcare.client;

/** Serialization in Java is a mechanism of writing the state of an object into a byte-stream for transfer
 *
 **/
import java.io.Serializable;

public class PatientObj implements Serializable {

	/** unique id used for serialization and deserialization **/
	private static final long serialVersionUID = 1059614087031313342L;
	private int Id;
	private String PName;
	private String UName;
	private String Pass;
	
	private String Address;
	private String MobNo;
	private String DOB;
	private String EMail;
	private String Emergency;



	public PatientObj(String pname, String uname, String pass, int age, String address, String mobno, String dob, String email, String emerg)
	{
		this.PName = pname;
		this.UName = uname;
		this.Pass = pass;
		
		this.Address = address;
		this.MobNo = mobno;
		this.DOB = dob;
		this.EMail = email;
		this.Emergency = emerg;
	}
	
	public PatientObj(){}
	
	public void setId(int id)
	{
		this.Id = id;
	}
	
	public int getId()
	{
		return this.Id;
	}
	public void setPName(String pname)
	{
		this.PName = pname; 
	}
	
	public String getPName()
	{
		return this.PName;
	}
	
	public void setUName(String uname)
	{
		this.UName = uname;
	}
	
	public String getUName()
	{
		return this.UName;
	}
	
	public void setPass(String pass)
	{
		this.Pass = pass;
	}
	
	public String getPass()
	{
		return this.Pass;
	}
	
	
	public void setAddress(String add)
	{
		this.Address = add;
	}
	
	public String getAddress()
	{
		return this.Address;
	}
	
	public void setMobNo(String num)
	{
		this.MobNo = num;
	}
	
	public String getMobNo()
	{
		return this.MobNo;
	}
	
	
	public void setDOB(String dob)
	{
		this.DOB = dob;
	}
	
	public String getDOB()
	{
		return this.DOB;
	}
	
	
	
	public void setEmail(String email)
	{
		this.EMail = email;
	}
	
	public String getEmail()
	{
		return this.EMail;
	}
	
	public void setEmergenecyNo(String emerg)
	{
		this.Emergency = emerg;
	}
	
	public String getEmergencyNo()
	{
		return this.Emergency;
	}
}
