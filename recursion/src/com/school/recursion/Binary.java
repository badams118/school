package com.school.recursion;

import javax.swing.*;
import java.awt.event.*;

public class Binary {
    private JFrame       window;
    private JLabel       prompt, result;
    private JTextField   entry;
    private JButton      calc;
    private int          decimal;
    private StringBuffer binary;

    /**
     * Constructor for the class Binary.
     * <p>
     * This class displays the binary version of a given decimal integer. 
     * 
     * @param  win  the user interface window
     */
    public Binary(JFrame win)
    {
        window = win;
        prompt = new JLabel("Enter an integer to convert to binary:");
        entry  = new JTextField();
        calc   = new JButton("Calculate");
        result = new JLabel();

        window.getContentPane().removeAll();
        
        prompt.setVerticalAlignment(JLabel.TOP);
        prompt.setSize(400, 100);
        prompt.setLocation(10, 5);
        
        window.add(prompt);
        
        entry.setSize(50, 20);
        entry.setLocation(220, 5);
        
        window.add(entry);
        
        calc.setSize(100, 25);
        calc.setLocation(75, 30);
        calc.addActionListener(calcAction());
        
        window.add(calc);
        
        window.repaint();
    }
    
    private ActionListener calcAction()
    {        
        ActionListener action = new ActionListener() {
            public void actionPerformed(ActionEvent event) {
                String sign = "";
                
                try {                    
                    decimal = Integer.parseInt(entry.getText());
                    binary  = new StringBuffer();
                    
                    if (decimal < 0) {
                        sign = "-";
                        decimal *= -1;
                    }
                    
                    calcBinary(decimal);
                    
                    addResult("The binary equivalent of " + decimal + " is " + 
                                sign + binary.reverse() + ".");
                } catch(NumberFormatException e) {
                    addResult("Invalid entry.  Try again.");
                }
            }
        };
        
        return action;
    }
    
    private void addResult(String s)
    {
        window.remove(result);
        
        result.setVerticalAlignment(JLabel.TOP);
        result.setSize(400, 100);
        result.setLocation(10, 65);
        result.setText(s);
        
        window.add(result);
        
        window.repaint();
    }
    
    private void calcBinary(int d)
    {   
        binary.append(d % 2);
        
        if (d / 2 > 0) {
            calcBinary(d / 2);
        }
    }
}