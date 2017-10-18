package com.school.recursion;

import javax.swing.*;
import java.awt.event.*;

public class Main {

    private JFrame    window;
    private JMenuBar  menuBar;
    private JMenu     menu;
    public  JLabel    welcome;
    private JMenuItem cannonball, power, binary, digiSum, exit;
    
    /**
     * Constructor for the class Main
     * <p>
     * This class creates the user window and menu. 
     */
    public Main()
    {   
        window  = new JFrame("Let's Do Recursion");
        menuBar = new JMenuBar();
        menu    = new JMenu("Methods");
        welcome = new JLabel();
        
        cannonball = new JMenuItem("Cannonball Recursion");
        power      = new JMenuItem("Power Function");
        binary     = new JMenuItem("Convert to Binary");
        digiSum    = new JMenuItem("Sum of Digits");
        exit       = new JMenuItem("Exit");
        
        welcome.setVerticalAlignment(JLabel.TOP);
        welcome.setHorizontalAlignment(JLabel.CENTER);
        welcome.setText("<html><center>Welcome to Recursion<br />" +
                        "Select your option from the menu above"   +
                        "</center></html>");
        
        cannonball.addActionListener(cannonballAction());
        power.addActionListener(powerAction());
        binary.addActionListener(binaryAction());
        digiSum.addActionListener(digiSumAction());
        exit.addActionListener(exitAction());
        
        window.setBounds(100, 100, 400, 300); 
        window.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        window.setVisible(true);
        
        menu.add(cannonball);
        menu.add(power);
        menu.add(binary);
        menu.add(digiSum);
        menu.add(exit);
        
        menuBar.add(menu);

        window.add(welcome);
        window.setJMenuBar(menuBar);
        window.repaint();
    }
    
    private ActionListener cannonballAction()
    {
        ActionListener action = new ActionListener() {
            public void actionPerformed(ActionEvent event) {
                Cannonballs cannonballs = new Cannonballs(window);
            }
        };
        
        return action;
    }
    
    private ActionListener powerAction()
    {
        ActionListener action = new ActionListener() {
            public void actionPerformed(ActionEvent event) {
                Power power = new Power(window);
            }
        };
        
        return action;
    }
    
    private ActionListener binaryAction()
    {
        ActionListener action = new ActionListener() {
            public void actionPerformed(ActionEvent event) {
                Binary binary = new Binary(window);
            }
        };
        
        return action;
    }
    
    private ActionListener digiSumAction()
    {
        ActionListener action = new ActionListener() {
            public void actionPerformed(ActionEvent event) {
                SumOfDigits digiSum = new SumOfDigits(window);
            }
        };
        
        return action;
    }
    
    private ActionListener exitAction()
    {
        ActionListener action = new ActionListener() {
            public void actionPerformed(ActionEvent event) {
                System.exit(0);
            }
        };
        
        return action;
    }
    
    /**
     * The entry point of the program.
     */
    public static void main(String[] args) {
        Main driver = new Main();
    }

}