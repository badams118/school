package com.school.solarsystem;

import javax.swing.*;
import java.awt.*;
import java.awt.event.*;

/** ThreeButtons and ThreeButtonFrame supplier Classes
 * Author: David D. Riley
 * Date: April, 2004
 *
 *  ThreeButtons supports button events for the ThreeButtonFrame class.
 *  This class is designed to be inherited and its methods overridden.
 */
public abstract class ThreeButtons  {  
	
	/** The method below is an event handler for button clicks on
	 *  the LEFT button of an object of type A3ButtonWindow 
     */
	public abstract void leftAction();
	
	/** The method below is an event handler for button clicks on
     *  the MID button of an object of type A3ButtonWindow 
     */
	public abstract void midAction();
	
	/** The method below is an event handler for button clicks on
     *  the RIGHT button of an object of type A3ButtonWindow 
     */
	public abstract void rightAction();
	
	
	/** The class below provides a JFrame that includes three JButtons (left, mid and right).
	 *	The event handling of these three buttons will be performed by the leftAction
	 *	midAction and rightAction methods of the subclass of ThreeButtons. 
     */
	protected class ThreeButtonFrame extends JFrame implements ActionListener{
		private static final long serialVersionUID = 1L;
		private JButton leftButton, midButton, rightButton;
		
		public ThreeButtonFrame(String s) {
			super(s);
			setBounds(20, 20, 600, 500);
			setVisible(true);
			Container pane = getContentPane();
			pane.setLayout(null);
			leftButton = new JButton("LEFT");
			leftButton.setBounds(100, 430, 100, 30);
			leftButton.addActionListener(this);
			pane.add(leftButton, 0);
			midButton = new JButton("MID");
			midButton.setBounds(250, 430, 100, 30);
			midButton.setText("MID");
			midButton.addActionListener(this);
			pane.add(midButton, 0);
			rightButton = new JButton("RIGHT");
			rightButton.setBounds(400, 430, 100, 30);
			rightButton.addActionListener(this);
			pane.add(rightButton, 0);
			pane.repaint();
		}
		
		/** Event Handler
         *  This method is called whenever any of the three
         *  buttons is clicked   
         */
		public void actionPerformed(ActionEvent e)  {
			if (e.getSource() == leftButton)
				leftAction();
			else if (e.getSource() == midButton)
				midAction();
			else if (e.getSource() == rightButton)
				rightAction();      
		}

	}

}