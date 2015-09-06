<?php


/*
 * Created on May 8, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class GwpmProfileVO extends GwpmCommand {

	/**
	 * @mandatory
	 * @message="Invalid value in Employee Id"
	 */
	var $userId;
	/**
	 * @mandatory
	 * @message="Invalid value in Email Id"
	 */
	var $user_email;
	/** 
	 * @mandatory
	 * @message="Invalid value for First name" 
	 */
	var $first_name;
	/** 
	 * @mandatory
	 * @message="Invalid value for Last name" 
	 */
	var $last_name;
	/** 
	 * @mandatory
	 * @message="Invalid value for Gender" 
	 */
	var $gwpm_gender;
	/** 
	 * @mandatory
	 * @message="Invalid value for Date of Birth" 
	 */
	var $gwpm_dob;
	var $description;

	var $gwpm_contact_no;
	/** 
	 * @mandatory
	 * @message="Invalid value for Martial Status" 
	 */
	var $gwpm_martial_status;
	/** 
	 * @mandatory
	 * @message="Invalid value for Zodiac Sign (Rassi)" 
	 */
	var $gwpm_zodiac;
	/** 
	 * @mandatory
	 * @message="Invalid value for Star Sign (Nakshatram)" 
	 */
	var $gwpm_starsign;
	/** 
	 * @mandatory
	 * @message="Invalid value for Sevvai Dosham" 
	 */
	var $gwpm_sevvai_dosham ;
	/** 
	 * @mandatory
	 * @message="Invalid value for Caste" 
	 */
	var $gwpm_caste;
	/** 
	 * @mandatory
	 * @message="Invalid value for Religion" 
	 */
	var $gwpm_religion;
	var $gwpm_physical ;
	var $gwpm_address ;
	/**
	 * @mandatory
	 * @message="qualification:Invalid value for Qualification##
	 * qualification_other:Invalid value for Qualification (Others)##
	 * specialization:Invalid value for Specialization##
	 * status:Invalid value for Work Status"
	 */
	var $gwpm_education ;
	var $gwpm_work ;
	var $gwpm_tmasvs ;
	var $gwpm_profile_photo ;

}