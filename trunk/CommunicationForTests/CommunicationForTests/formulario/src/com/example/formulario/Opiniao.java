package com.example.formulario;

public class Opiniao {
	
	String nome;
	String comentario;
	
	public Opiniao(String nome, String comentario){
		this.nome = nome;
		this.comentario = comentario;
	}
	
	public Opiniao() {
		// TODO Auto-generated constructor stub
	}

	void setNome(String nome){
		this.nome = nome;
	}
	
	void setComentario(String comentario){
		this.comentario = comentario;
	}
	
	String getNome(){
		return nome;
	}
	
	String getComentario(){
		return comentario;
	}
	
}
