package com.school.solarsystem;

import java.awt.Color;
import javax.swing.*;

/**
 * This class creates a star for the planets to orbit.
 * 
 * @author Brady Adams 
 * CS 1410 - 003
 * Spring 2007
 * Project 4
 */
public class Star
{
	// instance variable
	private Oval star;

	/**
	 * Constructor for objects of class Star
	 */
	public Star()
	{
		star = new Oval(325, 225, makeMass(), makeMass());
		star.setBackground(Color.yellow);
	}

	/**
	 * post: The star has a width & height of 50
	 */
	private int makeMass()
	{
	    return 50;
	}
	
	/**
	 * pre:  The star has been created
	 * post: The star is added to the window
	 */
	public void addTo(JFrame win)
	{
	    win.getContentPane().add(star, 0);
	}
}