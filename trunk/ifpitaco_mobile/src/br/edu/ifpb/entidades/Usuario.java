package br.edu.ifpb.entidades;


public class Usuario {
	private static String nome;
	private static int id;

	public static String getNome() {
		return nome;
	}

	public static void setNome(String nome) {
		Usuario.nome = nome;
	}

	public static int getId() {
		return id;
	}

	public static void setId(int id) {
		Usuario.id = id;
	}

}
