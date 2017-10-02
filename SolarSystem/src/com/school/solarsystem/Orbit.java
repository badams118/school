package com.school.solarsystem;

import java.awt.*;

/**
 * This class creates an orbit of a specified width & height.
 * 
 * @author Brady Adams 
 * CS 1410 - 003
 * Spring 2007
 * Project 4
 */
public class Orbit
{
	// instance variables 
    private Oval  orbit1;
    private Oval  orbit2;

	/**
	 * Constructor for objects of class Orbit
	 */
	public Orbit(int w, int h)
	{
		// initialize instance variables
        orbit1 = new Oval(17, 17, w, h);
        orbit2 = new Oval(orbit1.getX() + 1,     orbit1.getY() + 1, 
                          orbit1.getWidth() - 2, orbit1.getHeight() - 2);
                          
        // make the inner oval of the orbit match the window background
        orbit2.setBackground(Color.darkGray);
	}

	/**
	 * pre:  The orbit has been created
	 * post: Returns the orbit width
	 */
	public int getWidth()
	{
	    return orbit1.getWidth();
	}
	
	/**
	 * pre:  The orbit has been created
	 * post: Returns the orbit height
	 */
	public int getHeight()
	{
	    return orbit1.getHeight();
	}
	
	/**
	 * pre:  The orbit has been created
	 * post: Returns the orbit's x position
	 */
	public int getX()
	{
	    return orbit1.getX();
	}
	
	/**
	 * pre:  The orbit has been created
	 * post: Returns the orbit's y position
	 */
	public int getY()
	{
	    return orbit1.getY();
	}
	
	/**
	 * pre:  The orbit has been created
	 * post: The orbit has been added to the container
	 */
	public void addTo(Container c)
	{
        c.add(orbit1, 0);
        c.add(orbit2, 0);
	}
}